<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\Relationship;

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
                'created_at' => $this->created_at->format('Y-m-d h:m:s')
            ],
			'relationships' => new Relationship($this),
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ],
        ];
        /*return parent::toArray($request);*/
    }
}
