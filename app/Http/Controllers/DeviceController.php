<?php

namespace App\Http\Controllers;

use App\Helpers\Main;
use App\Http\Requests\StoreDeviceRequest;
use App\Models\Device;
use Illuminate\Http\Request;
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
}
