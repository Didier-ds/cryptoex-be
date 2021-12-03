<?php

namespace App\Http\Controllers;


use App\Helpers\ResponseBuilder;
use App\Http\Requests\LoginRequest;
use App\Models\Konstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        return response()->json(Konstants::EMAIL);
        $credentials = $request->only(Konstants::EMAIL, Konstants::PWORD);
        if (!Auth::attempt($credentials)) {
            return  response(ResponseBuilder::genErrorRes(Konstants::ERR_INVALID_CRED), Konstants::STATUS_BAD_CRED);
        }

        $activeUser = Auth::user();
        $token = $activeUser->createToken(Konstants::A_TOK)->accessToken;
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
