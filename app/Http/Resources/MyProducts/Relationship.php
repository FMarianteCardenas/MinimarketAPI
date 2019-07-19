<?php

namespace App\Http\Resources\MyProducts;

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
            'category' => (new CategoryResource($this->category))
        ];
        //return parent::toArray($request);
    }
}
