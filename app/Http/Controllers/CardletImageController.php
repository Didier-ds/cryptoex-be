<?php

namespace App\Http\Controllers;

use App\Models\CardletImage;
use App\Http\Requests\StoreCardletImageRequest;
use App\Http\Requests\UpdateCardletImageRequest;

class CardletImageController extends Controller
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
     * @param  \App\Http\Requests\StoreCardletImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCardletImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CardletImage  $cardletImage
     * @return \Illuminate\Http\Response
     */
    public function show(CardletImage $cardletImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CardletImage  $cardletImage
     * @return \Illuminate\Http\Response
     */
    public function edit(CardletImage $cardletImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCardletImageRequest  $request
     * @param  \App\Models\CardletImage  $cardletImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCardletImageRequest $request, CardletImage $cardletImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CardletImage  $cardletImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CardletImage $cardletImage)
    {
        //
    }
}
