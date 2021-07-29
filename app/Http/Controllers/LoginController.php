<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return  response([
                'message' => "Details does not match any User record"
            ], 500);
        }

        $activeUser = Auth::user();
        $token = $activeUser->createToken('auth-token')->accessToken;

        $response = [
            'user' => new UsersResource($activeUser),
            'token' => $token,
            'user_role' => $activeUser->roles()->pluck('name'),
            'token_type' => 'Bearer',
            'message' => "Welcome! You are loggedin as $activeUser->fullname"
        ];
        return response()->json($response, 200);
    }

    public function fetchUserBYToken(Request $request)
    {
        $activeUser = auth()->user();
        if ($activeUser === null) {
            return response()->json(['error' => 'Lacking Authorization'], 401);
        }

        $response = [
            'user' => new UsersResource($activeUser),
            'user_role' => $activeUser->roles()->pluck('name'),
            'message' => "Welcome! You are loggedin as $activeUser->fullname"
        ];

        return response()->json($response, 200);
    }
}
