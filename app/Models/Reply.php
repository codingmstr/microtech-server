<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'comment_id',
        'content',
        'likes',
        'dislikes',
        'active',
    ];

    public function user () {

        return $this->belongsTo(User::class);

    }
    public function comment () {

        return $this->belongsTo(Comment::class);

    }

}
