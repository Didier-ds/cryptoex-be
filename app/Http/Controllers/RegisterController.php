<?php

namespace App\Http\Controllers;

use App\Helpers\RoleManaer;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|min:8|confirmed'
        ]);

        $newUser = new User();
        $newUser->uuid = Str::uuid();
        $newUser->fullname = $request->fullname;
        $newUser->email = $request->email;
        $newUser->phone = $request->phone;
        $newUser->password = bcrypt($request->password);
        $newUser->save();

        $roleManaer = new RoleManaer();
        $roleManaer->createRole();



        event(new Registered($newUser));
        $newUser->assignRole('user');

        $token = $newUser->createToken('auth-token')->accessToken;


        return response()->json(
            [
                'message' => 'User Created Successfully',
                'user_attributes' => new UsersResource($newUser),
                'user_role' => $newUser->roles()->pluck('name'),
                'authorization' => 'Bearer',
                'token' => $token
            ],
            201
        );
    }
}
