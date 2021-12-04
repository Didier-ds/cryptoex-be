<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\ResponseBuilder;
use App\Http\Requests\CardletRequest;
use App\Http\Resources\CardletMainResource;
use App\Http\Resources\Cardletresource;
use App\Models\Card;
use App\Models\Cardlet;
use App\Models\Konstants;
use App\Models\User;
use App\Notifications\CardletNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CardletController extends Controller
{

    // ---------------- create and store  Cardlet ---------------- //    
    public function store(CardletRequest $request, $cardUuid)
    {
        // Check Auth
        $card = Card::where('uuid', $cardUuid)->first();
        if (!$card) {
            return  response(ResponseBuilder::genErrorRes(Konstants::MSG_404), Konstants::STATUS_401);
        }
        //save to store
        $user = auth()->user();
        $cardlet = Cardlet::create(array_merge($request->only('code', 'comment'), [
            'uuid' => Str::uuid(), 'name' => $card->name, 'type' => $card->type, 'rate' => $card->rate,
            'image' => Helpers::runImageUpload($request->file('image'), 'cardlets'), 'user_id' => $user->id
        ], Helpers::getTimeStamps()));


        $noticeData = [
            'body' => "A redeemable CryptoEx cardlet has been created by $user->fullname. Review and respond appropriately",
            'action' => 'Login To View Cardlet',
            'url' => url('https://cryptoex.netlify.app/#/login'),
            'last' => 'Thank you and have a blissfull day.'
        ];

        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new CardletNotification($noticeData));   // notify admins
        }

        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet',
            'data' => new Cardletresource($cardlet)
        ], 200);
    }


    public function cardletStatusChaneg(Request $request, $uuid)
    {
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('name')->toArray();

        if (!in_array('admin', $userRoles)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $cardlet = Cardlet::where('uuid', $uuid)->first();
        if (!$cardlet) {
            return response()->json(['message' => 'Cardlet not found'], 404);
        }


        $owner = $cardlet->user;
        $cardlet->update(['status' => $request->status]);

        $noticeData = [
            'body' => "The status of Your $cardlet->name  $cardlet->type cardlet has been reviewed and updated by CryptoEx.",
            'action' => 'Login To CryptoEx To View New Status',
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


    public function index(Request $request)
    {
        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $allCardlets = Cardlet::all();
        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet collection',
            'count' => count($allCardlets),
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
            'count' => count($allCardlets),
            'data' => CardletMainResource::collection($allCardlets)
        ], 200);
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
