<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardletMainResource;
use App\Http\Resources\Cardletresource;
use App\Models\Card;
use App\Models\Cardlet;
use App\Notifications\CardletNotification;
use Illuminate\Http\Request;
use Psy\Util\Str;

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
        $userCardlets = Cardlet::where('user_id', $userId)->get();
        return response()->json(
            [
                'status' => 'successfull',
                'type' => 'cardlet collection',
                'data' => Cardletresource::collection($userCardlets)
            ],
            200
        );
    }



    public function store(Request $request, $uuid)
    {

        $request->validate(['code' => 'required|string']);

        $card = Card::where('uuid', $uuid)->first();
        if (!$card) {
            return response()->json(['message' => 'Invalid Card Type'], 401);
        }

        $user = auth()->user();
        $cardlet = new Cardlet();
        $cardlet->uuid = Str::uuid();
        $cardlet->name = $card->name;
        $cardlet->type = $card->type;
        $cardlet->rate = $card->rate;
        $cardlet->comment = $request->comment;
        $cardlet->image = $request->image;

        $user->cardlet()->save($cardlet);

        $noticeData = [
            'body' => "A redeemable CryptoEx cardlet has been created by $user->name. Review and respond appropriately",
            'action' => 'View Cardlet',
            'url' => url('/admin-login'),
            'last' => 'Thankyou and have a blissfull time'
        ];

        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new CardletNotification($noticeData)); // notify admins
        }

        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet',
            'data' => new Cardletresource($cardlet)
        ], 200);
    }

    public function updateCardlet(Request $request, $uuid)
    {
        $user = auth()->user();
        $code = $request->validate(['code' => 'required|string']);
        $cardlet = Cardlet::where('uuid', $uuid)->first();

        if (!$cardlet) {
            return response()->json(['message' => 'Cardlet not found'], 404);
        }

        if ($user->id != $cardlet->user_id) {
            return response()->json(['message' => 'Lacking Authorization'], 401);
        }

        $cardlet->update($code);
        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet',
            'data' => new Cardletresource($cardlet)
        ], 200);
    }



    public function cardletStatusChaneg(Request $request, $uuid)
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name');

        if (!in_array('admin', $userRoles)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $cardlet = Cardlet::where('uuid', $uuid)->first();
        if (!cardlet) {
            return response()->json(['message' => 'Cardlet not found'], 404);
        }

        $owner = $cardlet->user;

        $cardlet->update(['status' => $request->status]);

        $noticeData = [
            'body' => "The status of Your $cardlet->name  $cardlet->type cardlet has been revied and updated by CryptoEx.",
            'action' => 'Login To CryptoEx',
            'url' => url('/login'),
            'last' => 'Thankyou and have a blissfull time'
        ];

        $owner->notify(new CardletNotification($noticeData)); // notify Card owner

        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet',
            'data' => new Cardletresource($cardlet)
        ], 200);
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
