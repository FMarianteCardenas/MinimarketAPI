<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'type' => 'sales',
            'id' => (string) $this->id,
            'attributes' => [
                'minimarket_id' => $this->minimarket_id,
                'seller_id' => $this->user_seller_id,
                'total_sale' => $this->total_sale,
            ],
            'relationships' => new Relationship($this),
            'links' => [
                'self' => route('sales.show', ['sales' => $this->id]),
            ],
        ];
        //return parent::toArray($request);
    }

}
