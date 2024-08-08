<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewController extends Controller {

    public function systems () {

        $products = Product::where('active', true)->where('allow_reviews', true)->get();
        $products = ProductResource::collection( $products );

        $clients = User::where('role', '3')->where('active', true)->where('allow_reviews', true)->get();
        $clients = UserResource::collection( $clients );

        return ['products' => $products, 'clients' => $clients];

    }
    public function default ( Request $req ) {
        
        return $this->success(self::systems());

    }
    public function index ( Request $req ) {

        $data = $this->paginate( Review::query(), $req );
        $items = ReviewResource::collection( $data['items'] );
        return $this->success(['items' => $items, 'total'=> $data['total']]);
        
    }
    public function show ( Request $req, Review $review ) {

        $item = ReviewResource::make( $review );
        return $this->success(['item' => $item] + self::systems());

    }
    public function store ( Request $req ) {

        $product = Product::where('id', $req->product_id)->where('allow_reviews', true)->where('active', true)->first();
        if ( !$product ) return $this->failed(['product' => 'not exists']);

        $data = [
            'admin_id' => $this->user()->id,
            'vendor_id' => $this->integer($req->vendor_id),
            'client_id' => $this->integer($req->client_id),
            'product_id' => $this->integer($req->product_id),
            'content' => $req->content,
            'rate' => $this->float($req->rate),
            'active' => $this->bool($req->active),
        ];

        $review = Review::create($data);
        return $this->success(['review' => $review]);

    }
    public function update ( Request $req, Review $review ) {

        $data = [
            'content' => $req->content,
            'rate' => $this->float($req->rate),
            'active' => $this->bool($req->active),
        ];

        $review->update($data);
        return $this->success();

    }
    public function delete ( Request $req, Review $review ) {

        $review->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Review::find($id)?->delete();
        return $this->success();

    }

}
