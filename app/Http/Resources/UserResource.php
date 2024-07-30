<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\File;

class UserResource extends JsonResource {

    public function toArray ( Request $req ) {

        $files = File::where('table', 'user')->where('column', $this->id);
        $image = $files->where('type', 'image')->latest()->first()?->url;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'role' => $this->role,
            'super' => $this->super,
            'supervisor' => $this->supervisor,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'image' => $image,
            'info' => ['name' => $this->name, 'image' => $image],
        ];

    }

}
