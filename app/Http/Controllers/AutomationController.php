<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Automation;
use App\Models\Device;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class AutomationController extends Controller
{
    public function index()
    {
        $device = Device::where('user_id', Auth::id())->first();
        if (!$device) {
            return redirect()->back()->with('error', 'Device tidak ditemukan.');
        }

        $automations = Automation::where('device_id', $device->id)->get();
        return view('automation.index', compact('automations'));
    }

    public function create()
    {
        return view('automation.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required',
            'pin' => 'required|string|max:10',
            'is_repeat' => 'nullable|boolean',
        ]);

        $device = Device::where('user_id', Auth::id())->first();
        if (!$device) {
            return redirect()->back()->with('error', 'Device tidak ditemukan.');
        }

        Automation::create([
            'name' => $request->name,
            'time' => $request->time,
            'pin' => $request->pin,
            'is_repeat' => $request->has('is_repeat') ? $request->is_repeat : true,
            'device_id' => $device->id,
        ]);

        return redirect()->route('automation.index')->with('success', 'Automation berhasil ditambahkan.');
    }

    public function edit(Automation $automation)
    {
        return view('automation.edit', compact('automation'));
    }

    public function update(Request $request, Automation $automation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required',
            'pin' => 'required|string|max:10',
            'is_repeat' => 'nullable|boolean',
        ]);

        $automation->update([
            'name' => $request->name,
            'time' => $request->time,
            'pin' => $request->pin,
            'is_repeat' => $request->has('is_repeat') ? $request->is_repeat : true,
        ]);

        return redirect()->route('automation.index')->with('success', 'Automation berhasil diperbarui.');
    }

    public function destroy(Automation $automation)
    {
        $automation->delete();
        return redirect()->route('automation.index')->with('success', 'Automation berhasil dihapus.');
    }

    public function checker()
    {
        try {
            $now = Carbon::now('Asia/Jakarta')->format('H:i'); // timezone sesuai lokal
            $automations = Automation::where('time', $now)->get();

            if ($automations->isEmpty()) {
                return response()->json(['message' => 'Tidak ada automation saat ini.']);
            }

            $client = new Client();
            $results = [];

            foreach ($automations as $automation) {
                $device = Device::find($automation->device_id);

                if (!$device) {
                    $results[] = [
                        'automation_id' => $automation->id,
                        'status' => 'failed',
                        'error' => 'Device tidak ditemukan'
                    ];
                    continue;
                }

                $stateValue = ($automation->state ?? 'off') === 'on' ? 1 : 0;

                $query = http_build_query([
                    'token' => $device->token,
                    $automation->pin => $stateValue
                ]);

                $url = rtrim(env('BLYNK_SERVER', 'https://blynk.cloud/external/api/'), '/') . "/update?" . $query;

                try {
                    $response = $client->get($url);
                    $results[] = [
                        'automation_id' => $automation->id,
                        'status' => 'success',
                        'http_code' => $response->getStatusCode()
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'automation_id' => $automation->id,
                        'status' => 'failed',
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
