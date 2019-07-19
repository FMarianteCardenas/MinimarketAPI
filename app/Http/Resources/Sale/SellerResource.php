<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->vendedor);
       return [
            'type' => 'users',
            'id' =>  (string)$this->id,
            'attributes' => [
                'name' => $this->name,
		'lastname' => $this->lastname,
                'email' => $this->email,
                'username'=> $this->username,
		'is_active' => (int)$this->is_active,
            ],
	    //'relationships' => new Relationship($this),
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ],
        ];
        //return parent::toArray($request);
    }
}
