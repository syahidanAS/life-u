<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtController extends Controller
{
    protected $current_user_id;
    public function generate($id, $email)
    {
        $payload = [
            'iat' => time(), // Waktu token dibuat
            'nbf' => time(), // Token mulai berlaku dari sekarang
            'exp' => time() + 3600, // Token kedaluwarsa dalam 1 jam (3600 detik)
            'id' => $id,
            'email' => $email,
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public function extract($jwt)
    {
        try {
            $extractedToken = explode(' ', $jwt)[1];
            $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
            // Pastikan token belum kedaluwarsa
            if (isset($decoded->exp) && $decoded->exp < time()) {
                throw new Exception('Token sudah kedaluwarsa.');
            }

            $this->current_user_id = User::where('id', $decoded->id)->first();
            return $this->current_user_id;
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid atau sudah kedaluwarsa.'
            ], 401);
        }
    }
}
