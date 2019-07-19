<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\MinimarketResource;

class Relationship extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'minimarket' => [
                'links' => [
                    'self' => route('minimarkets.show', ['minimarket' => $this->minimarket_id]),
                ],
                'data' => new MinimarketResource($this->minimarket),
            ]
        ];
        /* dd($request);
          return parent::toArray($request); */
    }

}
