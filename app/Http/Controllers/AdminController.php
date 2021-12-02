<?php

namespace App\Http\Controllers;

use App\Constants\Konstants;
use App\Http\Resources\MainUserResource;
use Illuminate\Http\Request;
use App\Models\User;


class AdminController extends Controller
{

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



    private function checkAuthorization(): bool
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name')->toArray();
        return in_array(Konstants::ROLE_ADMIN, $userRoles);
    }
}
