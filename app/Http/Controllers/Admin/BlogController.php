<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ReplyResource;
use App\Models\Blog;

class BlogController extends Controller {

    public function index ( Request $req ) {

        $blogs = BlogResource::collection( Blog::all() );
        return $this->success(['blogs' => $blogs]);

    }
    public function show ( Request $req, Blog $blog ) {

        $blog = BlogResource::make( $blog );
        return $this->success(['blog' => $blog]);

    }
    public function store ( Request $req ) {

        $data = [
            'title' => $req->title,
            'slug' => $this->slug($req->title),
            'description' => $req->description,
            'content' => $req->content,
            'phone' => $req->phone,
            'company' => $req->company,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'notes' => $req->notes,
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_likes' => $this->bool($req->allow_likes),
            'allow_dislikes' => $this->bool($req->allow_dislikes),
            'active' => $this->bool($req->active),
        ];

        $blog = Blog::create($data);
        $this->upload_files( $req->allFiles(), 'blog', $blog->id );
        return $this->success();

    }
    public function update ( Request $req, Blog $blog ) {

        $data = [
            'title' => $req->title,
            'slug' => $this->slug($req->title),
            'content' => $req->content,
            'description' => $req->description,
            'phone' => $req->phone,
            'company' => $req->company,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'notes' => $req->notes,
            'allow_comments' => $this->bool($req->allow_comments),
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
    public function comments ( Request $req, Blog $blog ) {

        $comments = CommentResource::for_blog( $blog->comments );
        return $this->success(['comments' => $comments]);

    }

}
