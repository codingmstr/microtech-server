<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\File;

class ProductResource extends JsonResource {

    protected static $with_category = true;

    public function toArray ( Request $req ) {

        $files = File::where('table', 'product')->where('column', $this->id);
        $images = FileResource::collection( $files->get() );
        $image = $files->where('type', 'image')->latest()->first()?->url;

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'location' => $this->location,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'description' => $this->description,
            'details' => $this->details,
            'includes' => $this->includes,
            'notes' => $this->notes,
            'views' => $this->views,
            'rate' => $this->rate,
            'allow_coupons' => $this->allow_coupons,
            'allow_orders' => $this->allow_orders,
            'allow_reviews' => $this->allow_reviews,
            'active' => $this->active,
            'reviews' => count($this->reviews),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'info' => ['name' => $this->name, 'image' => $image],
            'image' => $image,
            'images' => $images,
        ];

        if ( self::$with_category ) $data['category'] = CategoryResource::make( $this->category );
        return $data;

    }
    public static function for_category ( $resouce ) {

        self::$with_category = false;
        return parent::collection( $resouce );

    }

}
