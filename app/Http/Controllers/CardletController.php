<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardletMainResource;
use App\Http\Resources\Cardletresource;
use App\Models\Cardlet;
use Illuminate\Http\Request;

class CardletController extends Controller
{

    public function index(Request $request)
    {
        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $allCardlets = Cardlet::find();
        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet collection',
            'data' => CardletMainResource::collection($allCardlets)
        ], 200);
    }

    public function cardletsBySatus(Request $request)
    {
        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $allCardlets = Cardlet::where('status', $request->status)->get();
        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet collection',
            'data' => CardletMainResource::collection($allCardlets)
        ], 200);
    }

    public function userCardlets()
    {
        $userId = auth()->id();
        $userCardlets = Cardlet::where('user_id', $userId);
        return response()->json(
            [
                'status' => 'successfull',
                'type' => 'cardlet collection',
                'data' => Cardletresource::collection($userCardlets)
            ],
            200
        );
    }




    public function store(Request $request)
    {
        //
    }

    // public function show(Cardlet $cardlet)
    // {
    //     $uder
    //     return response()->json([
    //         'status' => 'successful',
    //         'type' => 'cardlet',
    //         'data' => $cardlet
    //     ], 200);
    // }


    public function edit(Cardlet $cardlet)
    {
        //
    }


    public function update(Request $request, Cardlet $cardlet)
    {
        //
    }


    public function destroy(Cardlet $cardlet)
    {
        //
    }


    /*
    *   Helper function Section
    */

    private function checkAuthorization(Request $request): bool
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name');
        if (in_array('admin', $userRoles)) {
            return true;
        } else {
            return false;
        }
    }
}
