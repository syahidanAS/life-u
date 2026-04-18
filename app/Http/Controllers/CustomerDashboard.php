<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Mapping;
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
        $device = Device::where('user_id', $user->id)->first();

        try {
            // Cek device + token
            if (!$device || !$device->token) {
                return response()->json([
                    'error' => 'Device or token not found',
                ], 400);
            }

            // Request ke Blynk API
            $client = new \GuzzleHttp\Client();

            $response = $client->request(
                'GET',
                env('BLYNK_SERVER', 'https://blynk.cloud/external/api/') . 'getAll?token=' . $device->token
            );

            if ($response->getStatusCode() != 200) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat fetching data',
                    'error_code' => $response->getStatusCode()
                ], 500);
            }

            $datas = json_decode($response->getBody(), true);

            /**
             * Ambil mapping pin => alias
             * contoh: ['v0' => 'Lampu Depan']
             */
            $mapping = Mapping::where('device_id', $device->id)->get();
            $pinMap = $mapping->pluck('alias', 'pin');

            $aliasToPin = $mapping->pluck('pin', 'alias');

            /**
             * Transform hasil Blynk:
             * v0 => Lampu Depan
             */
            $result = [];

            foreach ($datas as $pin => $value) {

                $label = $pinMap[$pin] ?? $pin;

                $result[$label] = [
                    'value' => $value,
                    'pin' => $pin
                ];
            }
            return response()->json([
                'status' => true,
                'data' => $result
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
            $url = env('BLYNK_SERVER', 'https://blynk.cloud/external/api/') . 'update?token=' . $token . '&' . urlencode($pin) . '=' . urlencode($value);

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
            $url = env('BLYNK_SERVER', 'https://blynk.cloud/external/api/') . 'isHardwareConnected?token=' . $token;
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
