<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Comment;

class ReplyController extends Controller {

    public function index ( Request $req ) {

        $replies = ReplyResource::collection( Reply::all() );
        return $this->success(['replies' => $replies]);

    }
    public function show ( Request $req, Reply $reply ) {

        $reply = ReplyResource::make( $reply );
        return $this->success(['reply' => $reply]);

    }
    public function store ( Request $req ) {

        $comment = Comment::where('id', $req->comment_id)->where('allow_replies', true)->where('active', true)->first();
        if ( !$comment ) return $this->failed(['comment' => 'not exists']);

        $data = [
            'user_id' => 1,
            'comment_id' => $this->integer($req->comment_id),
            'content' => $req->content,
            'active' => $this->bool($req->active),
        ];

        Reply::create($data);
        return $this->success();

    }
    public function update ( Request $req, Reply $reply ) {

        $data = [
            'content' => $req->content,
            'active' => $this->bool($req->active),
        ];

        $reply->update($data);
        return $this->success();

    }
    public function delete ( Request $req, Reply $reply ) {

        $reply->delete();
        return $this->success();

    }

}
