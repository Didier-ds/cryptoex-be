<?php

namespace App\Http\Controllers;

use App\Constants\Konstants;
use App\Helpers\ResponseBuilder;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return  response([
                'message' => Konstants::INVALID_CREDENTIALS_ERROR
            ], Konstants::SERVER_ERROR_CODE);
        }


        $activeUser = Auth::user();
        $token = $activeUser->createToken('auth-token')->accessToken;
        $response = ResponseBuilder::buildUserLoginRes($activeUser, $token);
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
}
