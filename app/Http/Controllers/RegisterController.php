<?php

namespace App\Http\Controllers;

use App\Models\Konstants;
use App\Helpers\ResponseBuilder;
use App\Models\RoleManager;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AdminRegResource;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class RegisterController extends Controller
{

    public function register(RegisterRequest $request)
    {

        $newUser = $this->runCreate($request);
        // Register user bank acc
        $newUser->account()->create($this->genDeafaultBankAcc($newUser->id));
        //def User role
        $newUser->assignRole(Konstants::ROLE_USER);
        return response()->json(new RegisterResource($newUser), Konstants::STATUS_OK);
    }


    //
    public function createAdmin(RegisterRequest $req)
    {
        // Validate initiator authorizaton status as owner 
        if (!RoleManager::checkUserRole(Konstants::ROLE_OWNER)) {
            return response(ResponseBuilder::genErrorRes(Konstants::ERR_LACK_AUTH), Konstants::STATUS_401);
        }
        // Create admin as a user
        $newAdmin = $this->runCreate($req);
        // Assigne Admin role to newly created admin
        $newAdmin->assignRole(Konstants::ROLE_ADMIN);
        return response()->json(
            new AdminRegResource([Konstants::EMAIL => $req->email, Konstants::PWORD => $req->password]),
            Konstants::STATUS_OK
        );
    }


    // Called when a user (user or an admin is to be created.)
    // the process fires a register event upon success
    private function runCreate(RegisterRequest $request): User
    {
        $roleManaer = new RoleManager();
        $roleManaer->createRole();
        $user = User::create(array_merge(
            $request->only(Konstants::FULLNAME, Konstants::EMAIL, Konstants::PHONE),
            [
                'uuid' => Str::uuid(), Konstants::PWORD =>  bcrypt($request->password),
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]
        ));

        event(new Registered($user));
        return $user;
    }

    private function genDeafaultBankAcc(int $id): array
    {
        return [
            'uuid' => Str::uuid(),
            Konstants::ACC_NO => (string)$id,
            Konstants::ACC_NAME => Konstants::DEFAULT,
            Konstants::BANK => Konstants::DEFAULT,
            'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
        ];
    }
}
