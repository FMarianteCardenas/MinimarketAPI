<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

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
                 'minimarket' => new MinimarketResource($this->minimarket),
                 'seller' => new SellerResource($this->seller),
                 'products' => new ProductCollection($this->products,$this->minimarket)
	       ];
        //return parent::toArray($request);
    }
}
