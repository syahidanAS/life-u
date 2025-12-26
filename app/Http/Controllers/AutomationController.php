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
            'state' => 'required|in:on,off',
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
            'state' => $request->state,
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
            'state' => 'required|in:on,off',
        ]);

        $automation->update([
            'name' => $request->name,
            'time' => $request->time,
            'pin' => $request->pin,
            'is_repeat' => $request->has('is_repeat') ? $request->is_repeat : true,
            'state' => $request->state,
        ]);

        return redirect()->route('automation.index')->with('success', 'Automation berhasil diperbarui.');
    }

    public function destroy(Automation $automation)
    {
        $automation->delete();
        return redirect()->route('automation.index')->with('success', 'Automation berhasil dihapus.');
    }
}
