<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\Relationship;


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
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'created_at'=> $this->created_at->format('Y-m-d h:m:s')
            ],
            'links' => [
                'self' => route('products.show', ['product' => $this->id]),
            ],
            'relationships' => new Relationship($this),
        ];
        //return parent::toArray($request);
    }
}
