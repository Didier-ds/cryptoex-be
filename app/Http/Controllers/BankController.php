<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseBuilder;
use App\Models\Konstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BankController extends Controller
{
    public function getAllBanks()
    {


        $head = ['Authorization' => 'Bearer ' . env("FLUTTERWAVE_KEY")];
        $res = Http::withHeaders($head)->get("https://api.flutterwave.com/v3/banks/NG");
        $banks = $res->json();

        // $curl = curl_init();
        // $key = env('BANK_SECRET');
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.flutterwave.com/v3/banks/NG",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => array(
        //         "Authorization: Bearer " . $key
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);


        return response()->json(ResponseBuilder::buildResourceCol($banks), Konstants::STATUS_OK);
    }
}
