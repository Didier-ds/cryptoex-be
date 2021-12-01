<?php

namespace App\Http\Controllers;

use App\Constants\Konstants;
use App\Helpers\ResponseBuilder;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\CardletCollection;
use App\Models\Cardlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Login function callable in via controller handler
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return  response([
                'message' => Konstants::INVALID_CREDENTIALS_ERROR
            ], Konstants::STATUS_ERROR);
        }

        $activeUser = Auth::user();
        $userCardlets = CardletCollection::collection(Cardlet::where('user_id', Auth::user()->id)->get());
        $token = $activeUser->createToken('auth-token')->accessToken;
        $response = ResponseBuilder::buildUserLoginRes($activeUser, $token, $userCardlets);
        return response()->json($response, Konstants::STATUS_OK);
    }

    //
    public function fetchUserBYToken(Request $request)
    {
        $activeUser = auth()->user();
        $userCardlets = CardletCollection::collection(Cardlet::where('user_id', Auth::user()->id)->get());
        $response = ResponseBuilder::buildUserLoginRes($activeUser, "", $userCardlets);
        return response()->json($response, 200);
    }
}
