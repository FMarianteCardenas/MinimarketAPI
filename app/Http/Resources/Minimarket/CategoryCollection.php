<?php

namespace App\Http\Resources\Minimarket;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => CategoryResource::collection($this->collection),
        ];
        //return parent::toArray($request);
    }
}
