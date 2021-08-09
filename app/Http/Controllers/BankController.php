<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankController extends Controller
{
    public function getAllBanks() {
      // logic to get all students goes here
      

        $curl = curl_init();
        $key = env('BANK_SECRET');
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/banks/NG",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$key
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        echo $response;
    }
}
