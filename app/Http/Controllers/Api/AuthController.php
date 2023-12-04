<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'role' => 'USER'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    public function login(Request $request) {
        $validateData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = User::where('email', $validateData['email'])->first();

        if(!$user || !Hash::check($validateData['password'], $user->password)) {
            return response()->json([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
