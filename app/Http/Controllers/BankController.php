<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseBuilder;
use App\Http\Requests\BankVetRequest;
use App\Http\Resources\VetBankResource;
use App\Models\Konstants;
use Illuminate\Support\Facades\Http;

class BankController extends Controller
{


    public function getAllBanks()
    {
        $head  = [Konstants::KEY_HEAD => 'Bearer ' . env("FLUTTERWAVE_KEY")];
        $res = Http::withHeaders($head)->get(Konstants::URL_FLUTTER_BANK);
        $banks = $res->json();
        if ($res->status() != 200) {
            return response(ResponseBuilder::genErrorRes($banks), Konstants::STATUS_ERROR);
        }
        return response()->json($banks, Konstants::STATUS_OK);
    }

    //
    //
    public function velidateBank(BankVetRequest $request)
    {
        $result = Http::get(Konstants::URL_MYLANCER .
            "account_number=" . $request->account_number . "&bank_code=" . $request->account_bank);
        $res = $result->json();
        if ($res->status() != 200) {
            return response(ResponseBuilder::genErrorRes($res), Konstants::STATUS_ERROR);
        }
        return response()->json(new VetBankResource($res));
    }
}
