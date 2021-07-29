<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Psy\Util\Str;

class CardController extends Controller
{

    public function index()
    {
        $allCards = Card::find();
        return response()->json([
            'type' => 'card collection',
            'count' => count($allCards),
            'data' => CardResource::collection($allCards)
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
            'code' => 'required|string',
        ]);
        $cardDetails = array_merge(['uuid' => Str::uuid()], ['comment' => $request->comment], $input);
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


    public function update(Request $request, Card $card)
    {

        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $input = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'rate' => 'required|string',
            'code' => 'required|string',
        ]);

        $cardDetails = array_merge(['uuid' => Str::uuid()], ['comment' => $request->comment], $input);
        $card = $card->update($cardDetails);
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
        $userRoles = $user->roles()->pluck('name');
        if (in_array('admin', $userRoles)) {
            return true;
        } else {
            return false;
        }
    }
}
