<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource {

    protected static $with_product = true;
    protected static $with_user = true;

    public function toArray ( Request $req ) {

        $data = [
            'id' => $this->id,
            'conetnt' => $this->content,
            'rate' => $this->rate,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'order_id' => $this->order?->id,
        ];

        if ( self::$with_product ) $data['product'] = ProductResource::make( $this->product );
        if ( self::$with_user ) $data['user'] = $this->user->role != 1 ? UserResource::make( $this->user ) : 'admin';

        return $data;

    }
    public static function for_product ( $resouce ) {

        self::$with_product = false;
        return parent::collection( $resouce );

    }
    public static function for_user ( $resouce ) {

        self::$with_user = false;
        return parent::collection( $resouce );

    }

}
