<?php

namespace App\Helpers;

use App\Http\Resources\AccountResource;
use App\Http\Resources\CardletCollection;
use App\Http\Resources\UsersResource;
use App\Models\User;

class ResponseBuilder
{
    public static function buildUserLoginRes(User $user, string $token)
    {

        return [
            'status' => 'success',
            'type' => 'user',
            'data' => [
                'bio' => new UsersResource($user),
                'user_role' => $user->roles()->pluck('name'),
                'bank_account' =>  new AccountResource($user->account),
                'cardlets' => CardletCollection::collection($user->cardlet)
            ],
            'token' => $token,
        ];
    }

    public static function genErrorRes(string $erroMsg): array
    {
        return ['message' => $erroMsg];
    }
}
