<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\File;

class CategoryController extends Controller {

    public function index ( Request $req ) {

        $categories = CategoryResource::collection( Category::all() );
        return $this->success(['categories' => $categories]);

    }
    public function show ( Request $req, Category $category ) {

        $category = CategoryResource::make( $category );
        return $this->success(['category' => $category]);

    }
    public function store ( Request $req ) {

        if ( Category::where('slug', $this->slug($req->name))->exists() ) {

            return $this->failed(['name' => 'exists']);

        }
        $data = [
            'name' => $req->name,
            'slug' => $this->slug($req->name),
            'company' => $req->company,
            'phone' => $req->phone,
            'location' => $req->location,
            'description' => $req->description,
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'active' => $this->bool($req->active),
            // 'image' => $this->upload_file($req->file('image_file'), 'category'),
        ];

        $category = Category::create($data);
        $this->upload_files([$req->file('image_file')], 'category', $category->id);
        return $this->success();

    }
    public function update ( Request $req, Category $category ) {

        if ( Category::where('slug', $this->slug($req->name))->where('id', '!=', $category->id)->exists() ) {

            return $this->failed(['name' => 'exists']);

        }
        if ( $req->file('image_file') ) {

            $file_id = File::where('table', 'category')->where('column', $category->id)->first()?->id;
            $this->delete_files([$file_id], 'category');
            $this->upload_files([$req->file('image_file')], 'category', $category->id);

        }
        $data = [
            'name' => $req->name,
            'slug' => $this->slug($req->name),
            'company' => $req->company,
            'phone' => $req->phone,
            'location' => $req->location,
            'description' => $req->description,
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'active' => $this->bool($req->active),
        ];

        $category->update($data);
        return $this->success();

    }
    public function delete ( Request $req, Category $category ) {

        $category->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Category::find($id)?->delete();
        return $this->success();

    }
    public function products ( Request $req, Category $category ) {

        $products = ProductResource::for_category( $category->products );
        return $this->success(['products' => $products]);

    }

}
