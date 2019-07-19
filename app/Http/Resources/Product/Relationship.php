<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\CategoryResource;


class Relationship extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'category' => [

                'data' => new CategoryResource($this->category),
            ]
        ];
        //return parent::toArray($request);
    }
}
