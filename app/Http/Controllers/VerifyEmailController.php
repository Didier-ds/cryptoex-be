<?php

namespace App\Http\Controllers;

use App\Models\Konstants;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify($id, $hash)
    {
        $newUser = User::find($id);
        abort_if(!$newUser, 403);
        abort_if(!hash_equals($hash, sha1($newUser->getEmailForVerification())), 403);

        // if ($newUser->verified_at === null) {
        //     $newUser->verified_at = Carbon::now();
        //     $newUser->save();
        // }

        if (!$newUser->hasVerifiedEmail()) {
            $newUser->markEmailAsVerified();
            event(new Verified($newUser));
        }
        return redirect()->away(Konstants::URL_VETTED);
    }
}
