<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseBuilder;
use App\Http\Resources\BankResource;
use App\Models\Konstants;
use Illuminate\Support\Facades\Http;

class BankController extends Controller
{
    public function getAllBanks()
    {
        $head = [Konstants::AUTH => 'Bearer ' . env("FLUTTERWAVE_KEY")];
        $res = Http::withHeaders($head)->get(Konstants::URL_FLUTTER_BANK);
        $banks = $res->json();

        return response()->json(
            ResponseBuilder::buildResourceCol(BankResource::collection($banks)),
            Konstants::STATUS_OK
        );
    }
}
