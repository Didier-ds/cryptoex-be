<?php

namespace App\Http\Resources;

use App\Models\Konstants;
use Illuminate\Http\Resources\Json\JsonResource;

class VetBankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status' => Konstants::MSG_OK,
            'collection type' => "bank",
            'data' => $request
        ];
    }
}
