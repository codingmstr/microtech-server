<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {

    protected static $with_product = true;
    protected static $with_user = true;
    protected static $with_coupon = true;

    public function toArray ( Request $req ) {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'notes' => $this->notes,
            'price' => $this->price,
            'coupon_discount' => $this->coupon_discount,
            'coupon_code' => $this->coupon_code,
            'secret_key' => $this->secret_key,
            'paid' => $this->paid,
            'status' => $this->status,
            'active' => $this->active,
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),
            'confirmed_at' => $this->confirmed_at?->format('Y-m-d H:i:s'),
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'ordered_at' => $this->ordered_at?->format('Y-m-d'),
        ];

        if ( self::$with_product ) $data['product'] = ProductResource::make( $this->product );
        if ( self::$with_user ) $data['user'] = UserResource::make( $this->user );
        if ( self::$with_coupon ) $data['coupon'] = CouponResource::make( $this->coupon );

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
    public static function for_coupon ( $resouce ) {

        self::$with_coupon = false;
        return parent::collection( $resouce );

    }

}
