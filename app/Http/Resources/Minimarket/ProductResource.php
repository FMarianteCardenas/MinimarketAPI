<?php

namespace App\Http\Resources\Minimarket;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'type' => 'products',
            'id' =>  (string)$this->id,
            'attributes' => [
                'code' => $this->code,
                'name' => $this->name,
		        'description' => $this->description,
                'buy_price' => $this->pivot->buy_price,
                'sale_price' => $this->pivot->sale_price,
                'stock' => $this->pivot->stock,
            ],
	    //'relationships' => new Relationship($this),
            'links' => [
                'self' => route('minimarket.product.id.get', ['minimarket_id' => $this->pivot->minimarket_id,'product_id' => $this->id]),
            ],
        ];
        //return parent::toArray($request);
    }
}
