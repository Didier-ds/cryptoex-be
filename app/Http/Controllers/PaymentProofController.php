<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use App\Http\Requests\StorePaymentProofRequest;
use App\Http\Requests\UpdatePaymentProofRequest;

class PaymentProofController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePaymentProofRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentProofRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentProof  $paymentProof
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentProof $paymentProof)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentProof  $paymentProof
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentProof $paymentProof)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentProofRequest  $request
     * @param  \App\Models\PaymentProof  $paymentProof
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentProofRequest $request, PaymentProof $paymentProof)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentProof  $paymentProof
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentProof $paymentProof)
    {
        //
    }
}
