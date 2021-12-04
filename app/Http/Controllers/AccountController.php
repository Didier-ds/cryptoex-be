<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{


    public function updateAccount(Request $request)
    {
        $input = $request->validate([
            'account_no' => 'required|string',
            'account_name' => 'required|string',
            'bank' => 'required|string',
        ]);

        $user = auth()->user();
        $userAccount = $user->account;
        $userAccount->update($input);
        return response()->json(['status' => 'success', 'type' => 'account', 'data' => $userAccount], 200);
    }
}
