<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseBuilder;
use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentProofResource;
use App\Models\Konstants;
use App\Models\RoleManager;

class PaymentProofController extends Controller
{

    public function index()
    {
        //
        if (!RoleManager::checkUserRole(Konstants::ROLE_ADMIN)) {
            return response(ResponseBuilder::genErrorRes(Konstants::ERR_LACK_AUTH), Konstants::STATUS_401);
        }

        $allProofs = PaymentProof::all();
        return response()->json(
            [
                'status' => Konstants::MSG_OK,
                'type' => 'profs',
                'count' => count($allProofs),
                'data' => PaymentProofResource::collection($allProofs)
            ],
            Konstants::STATUS_OK
        );
    }

    //
    public function fetchPendingProofs()
    {
        //
        if (!RoleManager::checkUserRole(Konstants::ROLE_ADMIN)) {
            return response(ResponseBuilder::genErrorRes(Konstants::ERR_LACK_AUTH), Konstants::STATUS_401);
        }

        $userProofs = PaymentProof::where('status', Konstants::PENDING)->get();
    }


    public function userProofs()
    {
        $userId = auth()->id();
        $userProofs = PaymentProof::where('user_id', $userId)->get();
        return response()->json(
            [
                'status' => 'successfull',
                'type' => 'proofs collection',
                'data' => PaymentProofResource::collection($userProofs)
            ],
            200
        );
    }
    public function store(Request $request)
    {
        //

        $request->validate([
            'shot'   => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'amount'    => ['required', 'string'],
        ]);

        $user = auth()->user();
        $file = $request->file('shot');
        $name = '/payment_shots/' . uniqid() . '.' . $file->extension();
        $file->move(public_path('payment_shots'), $name);



        $proof = new PaymentProof();
        $proof->uuid = Str::uuid();
        $proof->amount = $request->amount;
        $proof->status = 'pending';
        $proof->image = $name;
        $user->proof()->save($proof);



        return response()->json([
            'status' => 'successful',
            'type' => 'proofs',
            'data' => new PaymentProofResource($proof)
        ], 200);
    }
}
