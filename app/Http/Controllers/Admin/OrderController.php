<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CouponResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Coupon;

class OrderController extends Controller {

    public function index ( Request $req ) {

        $orders = OrderResource::collection( Order::all() );
        return $this->success(['orders' => $orders]);

    }
    public function default ( Request $req ) {

        $users = User::where('role', 3)->where('active', true)->where('allow_orders', true)->get();
        $products = Product::where('active', true)->where('allow_orders', true)->get();
        $coupons = Coupon::where('active', true)->get();

        $users = UserResource::collection( $users );
        $products = ProductResource::collection( $products );
        $coupons = CouponResource::collection( $coupons );

        return $this->success(['users' => $users, 'products' => $products, 'coupons' => $coupons]);

    }
    public function show ( Request $req, Order $order ) {

        $order = OrderResource::make( $order );
        return $this->success(['order' => $order]);

    }
    public function store ( Request $req ) {

        $product = Product::where('id', $req->product_id)->where('allow_orders', true)->where('active', true)->first();
        $user = User::where('role', 3)->where('id', $req->user_id)->where('active', true)->where('allow_orders', true)->first();
        $coupon = Coupon::where('id', $req->coupon_id)->where('active', true)->first();

        if ( !$user ) return $this->failed(['user' => 'not exists']);
        if ( !$product ) return $this->failed(['product' => 'not exists']);

        $price = $product->new_price;
        $secret_key = $this->random_key();
        $coupon_discount = 0;
        $coupon_code = null;
        $paid_at = null;
        $confirmed_at = null;
        $cancelled_at = null;

        if ( $coupon ) {
            $coupon_code = $coupon->name;
            $coupon_discount = $coupon->discount;
            $price -= $price * $coupon->discount / 100;
        }
        if ( $this->bool($req->paid) ) {
            $paid_at = date('Y-m-d H:i:s');
        }
        if ( $req->status == 'confirmed' ) {
            $confirmed_at = date('Y-m-d H:i:s');
        }
        if ( $req->status == 'cancelled' ) {
            $cancelled_at = date('Y-m-d H:i:s');
        }
        while ( Order::where('secret_key', $secret_key)->exists() ) {
            $secret_key = $this->random_key();
        }

        $data = [
            'user_id' => $this->integer($req->user_id),
            'product_id' => $this->integer($req->product_id),
            'coupon_id' => $this->integer($req->coupon_id),
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'address' => $req->address,
            'country' => $req->country,
            'city' => $req->city,
            'notes' => $req->notes,
            'secret_key' => $secret_key,
            'price' => $price,
            'coupon_discount' => $coupon_discount,
            'coupon_code' => $coupon_code,
            'paid' => $this->bool($req->paid),
            'status' => $req->status ?? 'pending',
            'active' => $this->bool($req->active),
            'paid_at' => $paid_at,
            'confirmed_at' => $confirmed_at,
            'cancelled_at' => $cancelled_at,
            'ordered_at' => $req->ordered_at,
        ];

        Order::create($data);
        return $this->success();

    }
    public function update ( Request $req, Order $order ) {

        if ( $this->bool($req->paid) && !$order->paid_at ) {
            $order->paid_at = date('Y-m-d H:i:s');
        }
        if ( $req->status == 'confirmed' && !$order->confirmed_at ) {
            $order->confirmed_at = date('Y-m-d H:i:s');
        }
        if ( $req->status == 'cancelled' && !$order->cancelled_at ) {
            $order->cancelled_at = date('Y-m-d H:i:s');
        }

        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'address' => $req->address,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'notes' => $req->notes,
            'paid' => $this->bool($req->paid),
            'ordered_at' => $req->ordered_at,
            'status' => $req->status,
            'active' => $this->bool($req->active),
        ];

        $order->update($data);
        return $this->success();

    }
    public function delete ( Request $req, Order $order ) {

        $order->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Order::find($id)?->delete();
        return $this->success();

    }

}
