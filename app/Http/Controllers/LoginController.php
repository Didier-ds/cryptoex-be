<?php

namespace App\Http\Controllers;


use App\Helpers\ResponseBuilder;
use App\Http\Requests\LoginRequest;
use App\Models\Konstants;
use App\Models\RoleManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    //
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(Konstants::EMAIL, Konstants::PWORD);
        if (!Auth::attempt($credentials)) {
            return  response(ResponseBuilder::genErrorRes(Konstants::ERR_INVALID_CRED), Konstants::STATUS_BAD_CRED);
        }

        $activeUser = Auth::user();
        $response = ResponseBuilder::buildUserLoginRes($activeUser, RoleManager::genToken($activeUser));
        return response()->json($response, Konstants::STATUS_OK);
    }

    //
    //
    public function fetchUserBYToken(Request $request)
    {
        $activeUser = auth()->user();
        $response = ResponseBuilder::buildUserLoginRes($activeUser, "");
        return response()->json($response, Konstants::STATUS_OK);
    }


    //
    public function managerLogin(LoginRequest $request)
    {
        $credentials = $request->only(Konstants::EMAIL, Konstants::PWORD);
        if (!Auth::attempt($credentials)) {
            return  response(ResponseBuilder::genErrorRes(Konstants::ERR_INVALID_CRED), Konstants::STATUS_BAD_CRED);
        }

        $owner = Auth::user();
        return response()->json(ResponseBuilder::buildNonUserLoginRes($owner, RoleManager::genToken($owner)));
    }
}
