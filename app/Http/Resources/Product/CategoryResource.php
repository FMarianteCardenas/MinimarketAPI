<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'type' => 'categories',
            'id' =>  (string)$this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => $this->created_at->format('Y-m-d h:m:s')
            ],
            'links' => [
                'self' => route('categories.show', ['category' => $this->id]),
            ],
        ];
        //return parent::toArray($request);
    }
}
