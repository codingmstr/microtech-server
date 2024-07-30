<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\File;

class CategoryResource extends JsonResource {

    public function toArray ( Request $req ) {

        $files = File::where('table', 'category')->where('column', $this->id);
        $image = $files->where('type', 'image')->latest()->first()?->url;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'company' => $this->company,
            'phone' => $this->phone,
            'location' => $this->location,
            'description' => $this->description,
            'allow_products' => $this->allow_products,
            'allow_orders' => $this->allow_orders,
            'allow_coupons' => $this->allow_coupons,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'products' => count($this->products),
            'info' => ['name' => $this->name, 'image' => $image],
            'image' => $image,
        ];

    }

}
