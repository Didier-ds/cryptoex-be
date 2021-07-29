<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'card' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'type' => (string) $this->type,
                'rate' => (string) $this->rate,
                'code' => (string) $this->code,
                'comment' => $this->comment
            ]
        ];
    }
}
