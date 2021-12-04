<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseBuilder;
use App\Http\Requests\BankVetRequest;
use App\Http\Resources\BankResource;
use App\Models\Konstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BankController extends Controller
{
    private $head = $head = [Konstants::AUTH => 'Bearer ' . env("FLUTTERWAVE_KEY")];
    //
    //
    public function getAllBanks()
    {
        $res = Http::withHeaders($this->head)->get(Konstants::URL_FLUTTER_BANK);
        $banks = $res->json();
        return response()->json($banks, Konstants::STATUS_OK);
    }

    //
    //
    public function velidateBank(BankVetRequest $request)
    {
        $body = $request->only('bank_code', 'account_number');
        $result = Http::withHeaders($this->head)->get(Konstants::URL_FLUTTER_RESOLVE, $body);
        $resHead = $result->headers();
        return response()->json($resHead);
    }
}
