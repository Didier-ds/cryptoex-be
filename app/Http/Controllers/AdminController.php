<?php

namespace App\Http\Controllers;

use App\Http\Resources\MainUserResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Output\ConsoleOutput;

class AdminController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'admin_key' => 'required|min:10'
        ]);

        $adminPass = "admin-0192h";
        if ($adminPass != $request->admin_key) {
            return response()->json(['message' => 'Lacking Authorization'], 401);
        }

        $newUser = new User();
        $newUser->uuid = Str::uuid();
        $newUser->fullname = $request->fullname;
        $newUser->email = $request->email;
        $newUser->phone = $request->phone;
        $newUser->password = bcrypt($request->password);
        $newUser->save();

        $this->createRole();

        event(new Registered($newUser));
        $newUser->assignRole('admin');
        $token = $newUser->createToken('auth-token')->accessToken;

        return response()->json(
            [
                'message' => 'Admin Created Successfully',
                'user_attributes' => new UsersResource($newUser),
                'user_role' => $newUser->roles()->pluck('name'),
                'authorization' => 'Bearer',
                'token' => $token
            ],
            201
        );
    }

    public function allUsers(Request $request)
    {

        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $allUser = User::all();
        if (!$allUser) {
            return response()->json(['message' => 'No Users currently'], 200);
        }
        return response()->json([
            'status' => 'successful',
            'type' => 'users collection',
            'count' => count($allUser),
            'data' => MainUserResource::collection($allUser)
        ], 200);
    }

    public function oneUser(Request $request, $userUuid)
    {

        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $user = User::where('uuid', $userUuid)->first();
        if (!$user) {
            return response()->json(['message' => 'No User Found'], 404);
        }
        return response()->json([
            'status' => 'successful',
            'type' => 'user',
            'data' => new MainUserResource($user)
        ], 200);
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
            Permission::create(['name' => 'write card']);
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

    private function checkAuthorization(Request $request): bool
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name')->toArray();
        if (in_array('admin', $userRoles)) {
            return true;
        } else {
            return false;
        }
    }
}
