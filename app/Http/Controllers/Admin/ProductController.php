<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Coupon;

class ProductController extends Controller {

    public function statistics ( $id ) {

        $orders = $this->charts( Order::where('product_id', $id) );
        $reviews = $this->charts( Review::where('product_id', $id) );
        $coupons = $this->charts( Coupon::where('product_id', $id) );

        return ['orders' => $orders, 'reviews' => $reviews, 'coupons' => $coupons];

    }
    public function systems () {

        $categories = Category::where('active', true)->where('allow_products', true)->get();
        $categories = CategoryResource::collection( $categories );

        $vendors = User::where('role', '2')->where('active', true)->where('allow_products', true)->get();
        $vendors = VendorResource::collection( $vendors );

        return ['categories' => $categories, 'vendors' => $vendors];

    }
    public function default ( Request $req ) {
        
        return $this->success(self::systems());

    }
    public function index ( Request $req ) {

        $data = $this->paginate( Product::query(), $req );
        $items = ProductResource::collection( $data['items'] );
        return $this->success(['items' => $items, 'total'=> $data['total']]);

    }
    public function show ( Request $req, Product $product ) {

        $item = ProductResource::make( $product );
        $data = ['item' => $item, 'statistics' => self::statistics($product->id)] + self::systems();
        return $this->success($data);

    }
    public function store ( Request $req ) {

        $data = [
            'admin_id' => $this->user()->id,
            'vendor_id' => $this->integer($req->vendor_id),
            'category_id' => $this->integer($req->category_id),
            'name' => $req->name,
            'company' => $req->company,
            'phone' => $req->phone,
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'location' => $req->location,
            'old_price' => $this->float($req->old_price),
            'new_price' => $this->float($req->new_price),
            'description' => $req->description,
            'details' => $req->details,
            'availability' => $req->availability,
            'policy' => $req->policy,
            'rules' => $req->rules,
            'safety' => $req->safety,
            'notes' => $req->notes,
            'includes' => $req->includes,
            'rate' => $this->float($req->rate),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'active' => $this->bool($req->active),
        ];

        $product = Product::create($data);
        $this->upload_files( $req->allFiles(), 'product', $product->id );
        return $this->success();

    }
    public function update ( Request $req, Product $product ) {

        $data = [
            'category_id' => $this->integer($req->category_id),
            'vendor_id' => $this->integer($req->vendor_id),
            'name' => $req->name,
            'company' => $req->company,
            'phone' => $req->phone,
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'location' => $req->location,
            'old_price' => $this->float($req->old_price),
            'new_price' => $this->float($req->new_price),
            'description' => $req->description,
            'details' => $req->details,
            'availability' => $req->availability,
            'policy' => $req->policy,
            'rules' => $req->rules,
            'safety' => $req->safety,
            'notes' => $req->notes,
            'includes' => $req->includes,
            'rate' => $this->float($req->rate),
            'allow_reviews' => $this->bool($req->allow_reviews),
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

}
