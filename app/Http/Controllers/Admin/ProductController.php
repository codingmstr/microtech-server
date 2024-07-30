<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller {

    public function index ( Request $req ) {

        $products = ProductResource::collection( Product::all() );
        return $this->success(['products' => $products]);

    }
    public function default ( Request $req ) {

        $categories = Category::where('active', true)->where('allow_products', true)->get();
        $categories = CategoryResource::collection( $categories );
        return $this->success(['categories' => $categories]);

    }
    public function show ( Request $req, Product $product ) {

        $product = ProductResource::make( $product );
        $categories = Category::where('active', true)->where('allow_products', true)->get();
        $categories = CategoryResource::collection( $categories );
        return $this->success(['product' => $product, 'categories' => $categories]);

    }
    public function store ( Request $req ) {

        if ( !Category::find($req->category_id) ) return $this->failed(['category' => 'not exists']);

        $data = [
            'category_id' => $this->integer($req->category_id),
            'name' => $req->name,
            'company' => $req->company,
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'old_price' => $this->float($req->old_price),
            'new_price' => $this->float($req->new_price),
            'description' => $req->description,
            'details' => $req->details,
            'includes' => $req->includes,
            'notes' => $req->notes,
            'rate' => $this->float($req->rate),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'active' => $this->bool($req->active),
        ];

        $product = Product::create($data);
        $this->upload_files( $req->allFiles(), 'product', $product->id );
        return $this->success();

    }
    public function update ( Request $req, Product $product ) {

        if ( !Category::find($req->category_id) ) return $this->failed(['category' => 'not exists']);

        $data = [
            'category_id' => $this->integer($req->category_id),
            'name' => $req->name,
            'company' => $req->company,
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'old_price' => $this->float($req->old_price),
            'new_price' => $this->float($req->new_price),
            'description' => $req->description,
            'details' => $req->details,
            'includes' => $req->includes,
            'notes' => $req->notes,
            'rate' => $this->float($req->rate),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'active' => $this->bool($req->active),
        ];

        $product->update($data);
        $this->upload_files( $req->allFiles(), 'product', $product->id );
        $this->delete_files( $this->parse($req->deleted_files), 'product' );
        return $this->success();

    }
    public function delete ( Request $req, Product $product ) {

        $product->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Product::find($id)?->delete();
        return $this->success();

    }
    public function orders ( Request $req, Product $product ) {

        $orders = OrderResource::for_product( $product->orders );
        return $this->success(['orders' => $orders]);

    }
    public function reviews ( Request $req, Product $product ) {

        $reviews = ReviewResource::for_product( $product->reviews );
        return $this->success(['reviews' => $reviews]);

    }

}
