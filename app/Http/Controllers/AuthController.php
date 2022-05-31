<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth:api')->except('login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return customResponse()
                ->data(null)
                ->message('Invalid credentials.')
                ->unauthorized()
                ->generate();
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = User::with([
                'profile',
                'employee'
            ])
            ->where('user_id', Auth::id())
            ->get()
            ->first();

        return customResponse()
            ->data([
                'access_token' => $accessToken,
                'user' => $user,
            ])
            ->message('You have successfully logged in.')
            ->success()
            ->generate();
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'User successfully signed out',
        ]);
    }

    public function refresh()
    {
        if (!Auth::guard('api')->check()) {
            return customResponse()
                ->data(null)
                ->message('Invalid credentials.')
                ->unauthorized()
                ->generate();
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = User::with([
                'profile',
                'employee'
            ])
            ->where('user_id', Auth::id())
            ->get()
            ->first();

        return customResponse()
            ->data([
                'access_token' => $accessToken,
                'user' => $user,
            ])
            ->message('You have successfully logged in.')
            ->success()
            ->generate();
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::user(),
        ]);
    }

    private function guard()
    {
        return Auth::guard();
    }
}
