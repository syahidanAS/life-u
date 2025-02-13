<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.login');
    }
    public function action(LoginRequest $request)
    {
        try {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, akun tidak ditemukan!',
                ], 401);
            } else {
                $return_url = null;
                if (Auth::user()->hasRole('Superadmin')) {
                    $return_url = route('home'); // Halaman admin
                } else {
                    $return_url = route('customer.dashboard'); // Halaman user biasa
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil melakukan login!',
                    'return_url' => $return_url
                ], 200);
            }
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error!',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); // Hancurkan sesi
        $request->session()->regenerateToken(); // Regenerasi token CSRF
        Cache::flush();
        return route('login');
    }
}
