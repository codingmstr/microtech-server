<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource {

    public function toArray ( Request $req ) {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'ip' => $this->ip,
            'agent' => $this->agent,
            'content' => $this->content,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];

    }

}
