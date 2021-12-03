<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentProofResource;

class PaymentProofController extends Controller
{

    public function index(Request $request)
    {
        //
        if (!$this->checkAuthorization($request)) {
            return response()->json(['message' => 'Lacking authorization'], 401);
        }

        $allProofs = PaymentProof::all();
        return response()->json([
            'status' => 'successful',
            'type' => 'cardlet collection',
            'count' => count($allProofs),
            'data' => PaymentProofResource::collection($allProofs)
        ], 200);
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
