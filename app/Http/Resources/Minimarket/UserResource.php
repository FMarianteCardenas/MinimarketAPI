<?php

namespace App\Http\Resources\Minimarket;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
    }
}
