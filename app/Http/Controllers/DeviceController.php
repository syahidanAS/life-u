<?php

namespace App\Http\Controllers;

use App\Helpers\HashidHelper;
use App\Helpers\Main;
use App\Http\Requests\StoreDeviceRequest;
use App\Models\Device;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Show Perangkat', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Device::with('user')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('token', function ($row) {
                    return $row->token;
                })
                ->addColumn('blynk_email', function ($row) {
                    return $row->blynk_email;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex gap-2">';
                    $btn .= "<a href='javascript:void(0)' class='btn btn-warning btn-sm edit-item' type='button' id='edit-item' data-id='" . Main::hashIdsEncode($row->id) . "'>Ubah</a>";
                    $btn .= "<a href='javascript:void(0)' class='btn btn-danger btn-sm delete-item' data-id='" . Main::hashIdsEncode($row->id) . "' data-name='" . $row->user->name . "'>Hapus</a>";
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'email_verified_at'])
                ->make(true);
        }
        return view('pages.device.configuration.index');
    }

    public function store(StoreDeviceRequest $request)
    {
        try {
            $query = new Device();
            $query->user_id = $request->user_id;
            $query->token = $request->token;
            $query->blynk_email = $request->blynk_email;
            $query->blynk_password = $request->blynk_password;
            $query->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data!',
            ], 201);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data karena terjadi kesalahan!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $user = Device::with('user')->findOrFail(Main::hashIdsDecode($id));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $user
            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data karena terjadi kesalahan!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $query = Device::find($request->id);
            $query->user_id = $request->user_id;
            $query->token = $request->token;
            $query->blynk_email = $request->blynk_email;
            $query->blynk_password = $request->blynk_password;
            $query->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data'
            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah data karena terjadi kesalahan!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $query = Device::find(Main::hashIdsDecode($id));
            $query->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data',
            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data karena terjadi kesalahan!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function controlApi(Request $request)
    {
        try {
            $request->validate([
                'pin'   => 'required|string',
                'value' => 'required|boolean',
                'user_id' => 'required|string',
            ]);
            $userId = HashidHelper::decrypt($request->user_id);
            $device = Device::where('user_id', $userId)->first();

            $pin   = $request->pin;
            $value = $request->value ? 1 : 0;

            $client = new Client();
            $url = rtrim(env('BLYNK_SERVER', 'https://blynk.cloud/external/api/'), '/') .
                "/update?token={$device->token}&{$pin}={$value}";

            $response = $client->get($url);

            if ($response->getStatusCode() !== 200) {
                return false;
            }

            return true;
        } catch (\Throwable $err) {
            return false;
        }
    }
}
