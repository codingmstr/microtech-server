<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ReplyResource;
use App\Models\Comment;
use App\Models\Blog;

class CommentController extends Controller {

    public function index ( Request $req ) {

        $comments = CommentResource::collection( Comment::all() );
        $blogs = BlogResource::collection( Blog::where('active', true)->where('allow_comments', true)->get() );
        return $this->success(['comments' => $comments, 'blogs' => $blogs]);

    }
    public function show ( Request $req, Comment $comment ) {

        $comment = CommentResource::make( $comment );
        return $this->success(['comment' => $comment]);

    }
    public function store ( Request $req ) {

        $blog = Blog::where('id', $req->blog_id)->where('allow_comments', true)->where('active', true)->first();
        if ( !$blog ) return $this->failed(['blog' => 'not exists']);

        $data = [
            'user_id' => 1,
            'blog_id' => $this->integer($req->blog_id),
            'content' => $req->content,
            'allow_replies' => $this->bool($req->allow_replies),
            'active' => $this->bool($req->active),
        ];

        Comment::create($data);
        return $this->success();

    }
    public function update ( Request $req, Comment $comment ) {

        $data = [
            // 'content' => $req->content,
            'allow_replies' => $this->bool($req->allow_replies),
            'active' => $this->bool($req->active),
        ];

        $comment->update($data);
        return $this->success();

    }
    public function delete ( Request $req, Comment $comment ) {

        $comment->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Comment::find($id)?->delete();
        return $this->success();

    }
    public function replies ( Request $req, Comment $comment ) {

        $replies = ReplyResource::for_comment( $comment->replies );
        return $this->success(['replies' => $replies]);

    }

}
