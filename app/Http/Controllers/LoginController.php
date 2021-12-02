<?php

namespace App\Http\Controllers;

use App\Constants\Konstants;
use App\Helpers\ResponseBuilder;
use App\Http\Requests\LoginRequest;
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
            ], Konstants::STATUS_ERROR);
        }

        $activeUser = Auth::user();
        $token = $activeUser->createToken('auth-token')->accessToken;
        $response = ResponseBuilder::buildUserLoginRes($activeUser, $token);
        return response()->json($response, Konstants::STATUS_OK);
    }


    //
    public function fetchUserBYToken(Request $request)
    {
        $activeUser = auth()->user();
        $response = ResponseBuilder::buildUserLoginRes($activeUser, "");
        return response()->json($response, 200);
    }
}
