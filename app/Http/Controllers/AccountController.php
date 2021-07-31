<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'account_no' => 'required|string',
            'account_name' => 'required|string',
            'bank' => 'required|string',
        ]);

        $user = auth()->user();
        $account = new Account();
        $account->uuid = Str::uuid();
        $account->account_no = $request->account_no;
        $account->account_name = $request->account_name;
        $account->bank = $request->bank;

        $user->account()->save($account);
        return response()->json(['status' => 'success', 'type' => 'account', 'data' => $account], 200);
    }

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
