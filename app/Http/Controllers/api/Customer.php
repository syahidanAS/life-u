<?php

namespace App\Http\Controllers\api;

use App\Helpers\HashidHelper;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Hashids\Hashids;
use Illuminate\Http\Request;

class Customer extends Controller
{
    protected $token;
    public function __construct(Request $request)
    {
        try {
            $extractedToken = explode(' ', $request->header('Authorization'))[1];
            $user = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
            $token = Device::where('user_id', HashidHelper::decrypt($user->id))->first()->token;
            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Device or token not found',
                ], 400);
            }
            $this->token = $token;
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid'
            ], 401);
        }
    }
    public function index()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', env('BLYNK_SERVER', 'https://blynk.cloud/external/api/') . 'getAll?token=' . $this->token);
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
                'message' => 'Berhasil fetching data',
                'data' => $datas
            ]);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat fetching data, silahkan hubungi administrator!'
            ], 500);
        }
    }

    public function updatePins(Request $request)
    {
        $pin = $request->pin;
        $value = $request->value ? 1 : 0;
        try {
            $client = new Client([
                'timeout' => 10,
                'connect_timeout' => 5
            ]);
            $response = $client->request('GET', env('BLYNK_SERVER', 'https://blynk.cloud/external/api/') . 'getAll?token=' . $this->token);

            $data = json_decode($response->getBody(), true);

            if (!array_key_exists($pin, $data)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pin tidak ditemukan!'
                ], 404);
            }

            $url = env('BLYNK_SERVER', 'https://blynk.cloud/external/api/') . 'update?token=' . $this->token . '&' . urlencode($pin) . '=' . urlencode($value);
            $response = $client->request('GET', $url);
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
            ]);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat fetching data, silahkan hubungi administrator!',
                'error_code' => $err->getMessage()
            ], 500);
        }
    }
}
