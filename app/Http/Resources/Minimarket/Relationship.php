<?php

namespace App\Http\Resources\Minimarket;

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
            'users' => (new UserCollection($this->users))->additional(['minimarket' => $this]),
            'categories' => (new CategoryCollection($this->categories))->additional(['minimarket' =>$this]),
            'products' => (new ProductCollection($this->products))->additional(['minimarket' => $this]),
	];
        //return parent::toArray($request);
    }
}
