<?php

namespace App\Http\Controllers\api;

use App\Helpers\HashidHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $user;
    protected $userDetail;
    protected $jwt_generator;
    public function __construct()
    {
        $this->user = new User();
        $this->jwt_generator = new JwtController();
    }
    public function generatejwt(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        try {
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email atau password tidak ditemukan'
                ], 401);
            } else {
                $user = $this->user->where(['email' => $credentials['email']])->first();
                $jwt = $this->jwt_generator->generate(HashidHelper::encrypt($user->id), $user->email);
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil melakukan login',
                    'access_token' => $jwt
                ], 200);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => __('msg.error'),
                'message' => __('msg.500'),
            ], 500);
        }
    }
}
