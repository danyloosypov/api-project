<?php

namespace App\Http\Controllers;

use App\Models\MySql\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'response_code' => '401',
                'status'        => 'error',
                'message'       => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'response_code' => '200',
            'status'        => 'success',
            'message'       => 'Login successful',
            'data' => [
                'user'  => $user,
                'token' => $accessToken,
            ],
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|max:20',
            'avatar'    => 'nullable|string|max:255',
            'role_id'   => 'nullable|integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => '422',
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'avatar'   => $request->avatar,
            'role_id'  => $request->role_id,
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'response_code' => '201',
            'status'        => 'success',
            'message'       => 'User registered successfully',
            'data' => [
                'user'  => $user,
                'token' => $accessToken,
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->token()->revoke();
        }

        return response()->json([
            'response_code' => '200',
            'status'        => 'success',
            'message'       => 'Logged out successfully',
        ]);
    }
}
