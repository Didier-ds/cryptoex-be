<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\Password_reset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    public function requestReset(Request $request)
    {
        $email = $request->validate(['email' => 'required|email']);
        $vetUser = User::where('email', $email)->first();
        if (!$vetUser) {
            return response()->json(['message' => 'user not found'], 404);
        }

        $code = random_bytes(8);
        $token = bin2hex($code);
        $vetUser['token'] = $token;

        $pReset = new Password_reset();
        $pReset->email = $request->email;
        $pReset->token = $token;
        $pReset->save();

        if ($vetUser) {
            Mail::to($vetUser)->send(new PasswordReset($vetUser));
            return response()->json(['message' => 'password-reset request sent'], 200);
        }
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        $vetToken = Password_reset::where('token', $request->token)->first();
        if (!$vetToken) {
            return response()->json(['message' => 'Input Error!'], 404);
        }

        $newPassword = bcrypt($request->password);
        $user = User::where('email', $vetToken->email)->find();
        $user->password = $newPassword;
        $user->save();

        if ($user) {
            return response()->json(['message' => 'password update successfully'], 200);
        } else {
            return response()->json(['message' => 'password update error!'], 200);
        }
    }
}