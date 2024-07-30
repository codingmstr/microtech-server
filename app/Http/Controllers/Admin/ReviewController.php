<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ProductResource;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller {

    public function index ( Request $req ) {

        $reviews = ReviewResource::collection( Review::all() );
        $products = ProductResource::collection( Product::where('active', true)->where('allow_reviews', true)->get() );
        return $this->success(['reviews' => $reviews, 'products' => $products]);

    }
    public function show ( Request $req, Review $review ) {

        $review = ReviewResource::make( $review );
        return $this->success(['review' => $review]);

    }
    public function store ( Request $req ) {

        $product = Product::where('id', $req->product_id)->where('allow_reviews', true)->where('active', true)->first();
        if ( !$product ) return $this->failed(['product' => 'not exists']);

        $data = [
            'user_id' => 1,
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
            // 'content' => $req->content,
            // 'rate' => $this->float($req->rate),
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
