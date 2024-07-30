<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource {

    protected static $with_comment = true;
    protected static $with_user = true;

    public function toArray ( Request $req ) {

        $data = [
            'id' => $this->id,
            'content' => $this->content,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];

        if ( self::$with_comment ) $data['comment'] = CommentResource::make( $this->comment );
        if ( self::$with_user ) $data['user'] = UserResource::make( $this->user );

        return $data;

    }
    public static function for_comment ( $resouce ) {

        self::$with_comment = false;
        return parent::collection( $resouce );

    }
    public static function for_user ( $resouce ) {

        self::$with_user = false;
        return parent::collection( $resouce );

    }

}
