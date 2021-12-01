<?php

namespace App\Helpers;

use App\Http\Resources\AccountResource;
use App\Http\Resources\UsersResource;
use App\Models\User;

class ResponseBuilder
{
    public static function buildUserLoginRes(User $user, string $token)
    {

        $userAccResource = ['account_name' => "", 'account_no'  => "", 'bank' => ""];
        if (($user->account != null)) {
            $userAccResource = new AccountResource($user->account);
        }

        return [
            'status' => 'success',
            'type' => 'user',
            'data' => [
                'bio' => new UsersResource($user),
                'user_role' => $user->roles()->pluck('name'),
                'bank_account' => $userAccResource,
                'cardlets' => [],
            ],
            'token' => $token,
        ];
    }
}
