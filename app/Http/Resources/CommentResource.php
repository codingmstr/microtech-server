<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource {

    protected static $with_blog = true;
    protected static $with_user = true;

    public function toArray ( Request $req ) {

        $data = [
            'id' => $this->id,
            'content' => $this->content,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'allow_replies' => $this->allow_replies,
            'active' => $this->active,
            'replies' => count($this->replies),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];

        if ( self::$with_blog ) $data['blog'] = BlogResource::make( $this->blog );
        if ( self::$with_user ) $data['user'] = $this->user->role != 1 ? UserResource::make( $this->user ) : 'admin';

        return $data;

    }
    public static function for_blog ( $resouce ) {

        self::$with_blog = false;
        return parent::collection( $resouce );

    }
    public static function for_user ( $resouce ) {

        self::$with_user = false;
        return parent::collection( $resouce );

    }

}
