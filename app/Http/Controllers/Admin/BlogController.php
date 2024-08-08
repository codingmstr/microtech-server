<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Models\Blog;

class BlogController extends Controller {

    public function index ( Request $req ) {

        $data = $this->paginate( Blog::query(), $req );
        $items = BlogResource::collection( $data['items'] );
        return $this->success(['items' => $items, 'total'=> $data['total']]);

    }
    public function show ( Request $req, Blog $blog ) {

        $item = BlogResource::make( $blog );
        return $this->success(['item' => $item]);

    }
    public function store ( Request $req ) {

        if ( Blog::where('slug', $this->slug($req->title))->exists() ) {

            return $this->failed(['title' => 'exists']);

        }
        $data = [
            'admin_id' => $this->user()->id,
            'vendor_id' => $this->integer($req->vendor_id),
            'title' => $req->title,
            'slug' => $this->slug($req->title),
            'description' => $req->description,
            'content' => $req->content,
            'company' => $req->company,
            'phone' => $req->phone,
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'location' => $req->location,
            'notes' => $req->notes,
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_replies' => $this->bool($req->allow_replies),
            'allow_likes' => $this->bool($req->allow_likes),
            'allow_dislikes' => $this->bool($req->allow_dislikes),
            'active' => $this->bool($req->active),
        ];

        $blog = Blog::create($data);
        $this->upload_files( $req->allFiles(), 'blog', $blog->id );
        return $this->success();

    }
    public function update ( Request $req, Blog $blog ) {

        if ( Blog::where('slug', $this->slug($req->title))->where('id', '!=', $blog->id)->exists() ) {

            return $this->failed(['title' => 'exists']);

        }
        $data = [
            'vendor_id' => $this->integer($req->vendor_id),
            'title' => $req->title,
            'slug' => $this->slug($req->title),
            'description' => $req->description,
            'content' => $req->content,
            'company' => $req->company,
            'phone' => $req->phone,
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'location' => $req->location,
            'notes' => $req->notes,
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_replies' => $this->bool($req->allow_replies),
            'allow_likes' => $this->bool($req->allow_likes),
            'allow_dislikes' => $this->bool($req->allow_dislikes),
            'active' => $this->bool($req->active),
        ];

        $blog->update($data);
        $this->upload_files( $req->allFiles(), 'blog', $blog->id );
        $this->delete_files( $this->parse($req->deleted_files), 'blog' );
        return $this->success();

    }
    public function delete ( Request $req, Blog $blog ) {

        $blog->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Blog::find($id)?->delete();
        return $this->success();

    }

}
