<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CouponResource;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;

class CouponController extends Controller {

    public function index ( Request $req ) {

        $coupons = CouponResource::collection( Coupon::all() );
        return $this->success(['coupons' => $coupons]);

    }
    public function show ( Request $req, Coupon $coupon ) {

        $coupon = CouponResource::make( $coupon );
        return $this->success(['coupon' => $coupon]);

    }
    public function store ( Request $req ) {

        if ( Coupon::where('name', $req->name)->exists() ) {

            return $this->failed(['name' => 'exists']);

        }
        $data = [
            'category_id' => $this->integer($req->category_id),
            'product_id' => $this->integer($req->product_id),
            'user_id' => $this->integer($req->user_id),
            'name' => $req->name,
            'discount' => $this->float($req->discount),
            'notes' => $req->notes,
            'active' => $this->bool($req->active),
        ];

        $coupon = Coupon::create($data);
        return $this->success(['coupon' => CouponResource::make( $coupon )]);

    }
    public function update ( Request $req, Coupon $coupon ) {

        if ( Coupon::where('name', $req->name)->where('id', '!=', $coupon->id)->exists() ) {

            return $this->failed(['name' => 'exists']);

        }
        $data = [
            'category_id' => $this->integer($req->category_id),
            'product_id' => $this->integer($req->product_id),
            'user_id' => $this->integer($req->user_id),
            'name' => $req->name,
            'discount' => $this->float($req->discount),
            'notes' => $req->notes,
            'active' => $this->bool($req->active),
        ];

        $coupon->update($data);
        return $this->success();

    }
    public function delete ( Request $req, Coupon $coupon ) {

        $coupon->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Coupon::find($id)?->delete();
        return $this->success();

    }
    public function orders ( Request $req, Coupon $coupon ) {

        $orders = OrderResource::for_coupon( $coupon->orders );
        return $this->success(['orders' => $orders]);

    }

}
