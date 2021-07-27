<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersControllers extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|min:8|confirmed'
        ]);

        $user  = User::find(auth()->id());
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->update();

        return response()->json(
            [
                'message' => 'Account Updated Successfully',
                'user_attributes' => new UsersResource($user),
            ],
            201
        );
    }
}
