<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Sale;

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
        
        $sale = Sale::findOrFail($this->pivot->sale_id);
        $minimarket = $sale->minimarket;
        $product_result = $minimarket->products->where('id','=',$this->id)->first();
        $buy_price = $product_result->pivot->buy_price;
        //dd($buy_price);
        //dd($this);
        return [
            'type' => 'products',
            'id' =>  (string)$this->id,
            'attributes' => [
                'name' => $this->name,
		        'code' => $this->code,
                'buy_price' => $buy_price,
                'quantity' => $this->pivot->quantity,
            ],
	    //'relationships' => new Relationship($this),
            'links' => [
                'self' => route('minimarket.product.id.get', ['minimarket_id' => $minimarket->id,'product_id' => $this->id]),
            ],
        ];
        //return parent::toArray($request);
    }
}
