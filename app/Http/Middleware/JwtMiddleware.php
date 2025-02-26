<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mohon masukkan token yang valid!'
                ], 401);
            }

            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            // Periksa apakah token sudah kedaluwarsa
            if (isset($decoded->exp) && $decoded->exp < time()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token sudah kedaluwarsa. Silakan login kembali.'
                ], 401);
            }

            // Simpan user ke request agar bisa digunakan di controller
            $request->attributes->add(['user' => $decoded]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid atau sesi Anda sudah habis.'
            ], 401);
        }

        return $next($request);
    }
}
