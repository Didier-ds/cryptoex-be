<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\ResponseBuilder;
use App\Http\Requests\ForgetPasswordReq;
use App\Http\Requests\ResetPwordReq;
use App\Mail\PasswordReset;
use App\Models\Konstants;
use App\Models\Password_reset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    public function requestReset(ForgetPasswordReq $request)
    {

        // Vet User email existence
        $vetUser = User::where(Konstants::EMAIL, $request->only(Konstants::EMAIL))->first();
        if (!$vetUser) {
            return response(ResponseBuilder::genErrorRes(Konstants::MSG_404), Konstants::STATUS_NOT_FOUND);
        }
        //
        // Recovery Token
        $token = Helpers::GenHecCode(8);
        DB::table('password_resets')->insert([
            Konstants::EMAIL => $request->email,
            Konstants::TOKEN => $token,
            'created_at' => now()
        ]);

        // Generate Email Data 
        $data = [
            Konstants::ROLE_USER => $vetUser,
            Konstants::URL => url(Konstants::URL_BASE . "#/password-reset?key=" . $token)
        ];
        // Send mail and return appropriate response
        if ($vetUser) {
            Mail::to($vetUser)->send(new PasswordReset($data));
            return response()->json(['message' => Konstants::MESSAGE_PWORD_RESET], Konstants::STATUS_OK);
        }
    }


    public function passwordReset(ResetPwordReq $request)
    {

        $vetToken = Password_reset::where(Konstants::TOKEN, $request->token)->first();
        if (!$vetToken) {
            return response(ResponseBuilder::genErrorRes(Konstants::ERR_INVALID_INPUT), Konstants::STATUS_BAD_CRED);
        }
        // extract new Password 
        $newPassword = bcrypt($request->password);
        //update user password
        $user = User::where('email', $vetToken->email)->find();
        $user->password = $newPassword;
        $user->save();

        if ($user) {
            return response()->json(['message' => Konstants::MESSAGE_SUCCESS], 200);
        } else {
            return response(ResponseBuilder::genErrorRes(Konstants::MSG_500), Konstants::STATUS_ERROR);
        }
    }
}
