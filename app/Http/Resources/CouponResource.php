<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource {

    public function toArray ( Request $req ) {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'discount' => $this->discount,
            'uses' => $this->uses,
            'notes' => $this->notes,
            'active' => $this->active,
            'orders' => count($this->orders),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];

    }

}
