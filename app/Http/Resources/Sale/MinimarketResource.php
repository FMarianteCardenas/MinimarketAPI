<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimarketResource extends JsonResource
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
                    'type' => 'minimarkets',
                    'id' =>  (string)$this->id,
                    'attributes' => [
                        'name' => $this->name,
                        'address' => $this->address,
                        'patent' => $this->patent,
                        'is_active'=> (int)$this->is_active,
                    ],
                    'links' => [
                        'self' => route('minimarkets.show', ['minimarket' => $this->id]),
                    ],
              ];
        //return parent::toArray($request);
    }
}
