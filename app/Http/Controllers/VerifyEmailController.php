<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify($id, $hash)
    {
        $newUser = User::find($id);
        abort_if(!$newUser, 403);
        abort_if(!hash_equals($hash, sha1($newUser->getEmailForVerification())), 403);

        if (!$newUser->hasVerifiedEmail()) {
            $newUser->markEmailAsVerified();
            event(new Verified($newUser));
        }

        return response()->json([
            'message' => 'Email verified'
        ]);
    }
}
