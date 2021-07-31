<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
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
        $response = $this->buildRes($activeUser, $token);
        return response()->json($response, 200);
    }

    public function fetchUserBYToken(Request $request)
    {
        $activeUser = auth()->user();
        if ($activeUser === null) {
            return response()->json(['error' => 'Lacking Authorization'], 401);
        }

        $response = $this->buildRes($activeUser, null);
        return response()->json($response, 200);
    }


    private function buildRes(User $user, $token)
    {
        if ($user->account != null) {
            return  $response = [
                'status' => 'success',
                'type' => 'user',
                'user' => new UsersResource($user),
                'account' => new AccountResource($user->account),
                'user_role' => $user->roles()->pluck('name'),
                'token_type' => 'Bearer',
                'token' => $token,
                'message' => "Welcome! You are logged in as $user->fullname"
            ];
        } else {
            return  $response = [
                'status' => 'success',
                'type' => 'user',
                'user' => new UsersResource($user),
                'account' => null,
                'user_role' => $user->roles()->pluck('name'),
                'token_type' => 'Bearer',
                'token' => $token,
                'message' => "Welcome! You are logged in as $user->fullname"
            ];
        }
    }
}
