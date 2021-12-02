<?php

namespace App\Http\Controllers;

use App\Constants\Konstants;
use App\Helpers\RoleManager;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $this->runRoleSetup();
        $newUser = User::create(array_merge(
            $request->only('fullname', 'email', 'phone'),
            [
                'uuid' => Str::uuid(), 'password' =>  bcrypt($request->password),
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]
        ));

        event(new Registered($newUser));
        $newUser->assignRole('user');
        return response()->json(new RegisterResource($newUser), Konstants::STATUS_OK);
    }



    private function runRoleSetup()
    {
        $roleManaer = new RoleManager();
        $roleManaer->createRole();
    }
}
