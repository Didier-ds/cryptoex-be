<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Output\ConsoleOutput;

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

        $this->createRole();

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


    //  --------------------- Helper functions ---------------------------- //

    private function isRoleExist($role_name): bool
    {
        return DB::table('roles')->where('name', $role_name)->count() > 0;
    }

    private function isPermissionExist($pem_name): bool
    {
        return DB::table('permissions')->where('name', $pem_name)->count() > 0;
    }


    private function createRole()
    {
        $out = new ConsoleOutput(); // for console logging

        if (!$this->isPermissionExist('crud user')) {
            Permission::create(['name' => 'crud user']);
            $out->writeln("1");
        }


        if (!$this->isPermissionExist('crud admin')) {
            Permission::create(['name' => 'crud admin']);
            $out->writeln("2");
        }

        if (!$this->isPermissionExist('write card')) {
            Permission::create(['name' => 'create card']);
            $out->writeln("2");
        }

        if (!$this->isRoleExist('owner')) {
            $role = new Role();
            $role->name = "owner";
            $role->save();
            $role->syncPermissions(['crud user', 'crud admin']);
            $out->writeln("3");
        }

        if (!$this->isRoleExist('admin')) {
            $role = new Role();
            $role->name = "admin";
            $role->save();
            $role->givePermissionTo('crud user');
            $out->writeln("4");
        }

        if (!$this->isRoleExist('user')) {
            $role = new Role();
            $role->name = "user";
            $role->save();
            $out->writeln("5");
        }
    }
}
