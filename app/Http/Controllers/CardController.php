<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CardController extends Controller
{

    public function index(Request $request)
    {
        $cards = null;
        if ($request->name == null || $request->name == "") {
            $cards = Card::all();
        } else {
            $cards = Card::where('name', $request->name)->get();
        }

        return response()->json([
            'type' => 'card collection',
            'count' => count($cards),
            'data' => CardResource::collection($cards)
        ]);
    }


    public function store(Request $request)
    {

        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $input = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'rate' => 'required|string',
        ]);

        $check = DB::table('cards')->where('name', $request->name)->where('type', $request->type)->count();
        if ($check > 0) {
            return response()->json(['message' => "$request->name gift card of $request->type type already exist"], 400);
        }

        $cardDetails = array_merge(['uuid' => Str::uuid()], $input);
        $newcard = Card::create($cardDetails);

        if ($newcard) {
            return response()->json(['status' => 'successful', 'type' => 'card', 'data' => new CardResource($newcard)], 200);
        } else {
            return response()->json(['message' => 'Error! Something went wrong.'], 500);
        }
    }


    public function show(Card $card)
    {
        return new CardResource($card);
    }


    public function edit(Card $card)
    { }


    public function update(Request $request, $id)
    {

        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }
        $card = Card::find($id);

        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'rate' => 'required|string',
        ]);

        $card->name = strtolower($request->name);
        $card->type = strtolower($request->type);
        $card->rate = strtolower($request->rate);
        $card->save();

        if ($card) {
            return response()->json(['status' => 'successful', 'type' => 'card', 'data' => new CardResource($card)], 200);
        } else {
            return response()->json(['message' => 'Error! Something went wrong.'], 500);
        }
    }

    public function cardRateChange(Request $request, $uuid)
    {

        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $request->validate(['rate' => 'required']);
        $card = Card::where('uuid', $uuid)->first();
        $card->rate = $request->rate;
        $card->save();
        if ($card) {
            return response()->json(['status' => 'successful', 'type' => 'card', 'data' => new CardResource($card)], 200);
        } else {
            return response()->json(['message' => 'Error! Something went wrong.'], 500);
        }
    }


    public function destroy(Card $card)
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name');
        if (!in_array('admin', $userRoles)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }
        $card->delete();
        return response(null, 204);
    }


    /*
    *   Helper function Section
    */

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
