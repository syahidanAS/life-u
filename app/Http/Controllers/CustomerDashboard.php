<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboard extends Controller
{
    public function index()
    {
        return view('customer-dashboard.home');
    }

    public function getPins()
    {
        $user = Auth::user()->load(['device']);
        $token = Device::where('user_id', $user->id)->first()->token;
        try {
            // Cek apakah user memiliki device dan token
            if (!$token) {
                return response()->json([
                    'error' => 'Device or token not found',
                ], 400);
            }

            $client = new Client();
            $response = $client->request('GET', env('BLYNK_SERVER') . 'getAll?token=' . $token);
            $datas = json_decode($response->getBody(), true);

            if ($response->getStatusCode() != 200) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat fetching data, silahkan hubungi administrator!',
                    'error_code' => $response->getStatusCode()
                ], 500);
            }
            return response()->json([
                'status' => true,
                'data' => $datas
            ]);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat fetching data, silahkan hubungi administrator!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function updatePins(Request $request)
    {
        $user = Auth::user()->load(['device']);
        $token = Device::where('user_id', $user->id)->first()->token;
        $pin = $request->pin;
        $value = $request->value ? 1 : 0;

        try {
            // Cek apakah user memiliki device dan token
            if (!$token) {
                return response()->json([
                    'error' => 'Device or token not found',
                ], 400);
            }

            $client = new Client();

            // Menyusun URL dengan benar
            $url = env('BLYNK_SERVER') . 'update?token=' . $token . '&' . urlencode($pin) . '=' . urlencode($value);

            // Melakukan permintaan GET ke server Blynk dan menunggu respons
            $response = $client->request('GET', $url);

            // Mengambil data dari respons
            $datas = json_decode($response->getBody(), true);

            // Memeriksa status code respons
            if ($response->getStatusCode() != 200) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat fetching data, silahkan hubungi administrator!',
                    'error_code' => $response->getStatusCode()
                ], 500);
            }

            // Mengembalikan respons sukses setelah permintaan berhasil
            return response()->json([
                'status' => true,
                'message' => $value == 1 ? 'Berhasil menyalakan lampu' : 'Berhasil mematikan lampu',
                'data' => $datas // Menambahkan data dari server Blynk jika perlu
            ]);
        } catch (\Throwable $err) {
            // Menangani kesalahan yang mungkin terjadi selama permintaan
            return response()->json([
                'status' => false,
                'message' => 'Gagal, silahkan hubungi administrator!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function getDeviceStatus()
    {
        $user = Auth::user()->load(['device']);
        $token = Device::where('user_id', $user->id)->first()->token;


        try {
            if (!$token) {
                return response()->json([
                    'error' => 'Device or token not found',
                ], 400);
            }
            $client = new Client();
            $url = env('BLYNK_SERVER') . 'isHardwareConnected?token=' . $token;
            $response = $client->request('GET', $url);
            $datas = json_decode($response->getBody(), true);
            if ($response->getStatusCode() != 200) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat fetching data, silahkan hubungi administrator!',
                    'error_code' => $response->getStatusCode()
                ], 500);
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mendapatkan status',
                'data' => $datas
            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal, silahkan hubungi administrator!',
                'error' => $err->getMessage()
            ], 500);
        }
    }
}
