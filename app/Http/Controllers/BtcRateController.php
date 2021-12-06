<?php

namespace App\Http\Controllers;
use App\Http\Resources\BtcRateResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BtcRate;
use App\Models\Konstants;
use Illuminate\Support\Facades\Http;


class BtcRateController extends Controller
{
    //
    public function index(Request $request)
    {
        $res = Http::get(Konstants::URL_BTC_RATE);
        $data = $res->json();
        $btc_rate = BtcRate::all();
        return response()->json([
            'type' => 'rate collection',
            'count' => count($btc_rate),
            'btc' => $data,
            'data' => BtcRateResource::collection($btc_rate)
        ]);
    }
    public function updateRate(Request $request)
    {
         if (!$this->checkAuthorization($request)) {
             return response()->json(['message' => 'Lacking authorization'], 401);
         }
        $input = $request->validate([
            'rate' => 'required|string'
        ]);
        $btc_rate = BtcRate::find(1);
        $btc_rate->rate = $request->rate;
        $btc_rate->save();
        return response()->json(['status' => 'success', 'type' => 'rate', 'data' => new BtcRateResource($btc_rate)], 200);
    }
    public function store(Request $request)
    {
        $input = $request->validate([
            'rate' => 'required|string',
            
        ]);
        $data = array_merge(['uuid' => Str::uuid()], $input);
        $newrate = BtcRate::create($data);

        if ($newrate) {
            return response()->json(['status' => 'successful', 'type' => 'card', 'data' => new BtcRateResource($newrate)], 200);
        } else {
            return response()->json(['message' => 'Error! Something went wrong.'], 500);
        }
    }

    private function checkAuthorization(Request $request): bool
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name')->toArray();
        if (in_array('admin', $userRoles)) {
            return true;
        } else {
            return false;
        }
    }
}
