<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'company',
        'phone',
        'country',
        'city',
        'location',
        'notes',
        'views',
        'likes',
        'dislikes',
        'allow_comments',
        'allow_likes',
        'allow_dislikes',
        'active',
    ];

    public function comments () {

        return $this->hasMany(Comment::class);

    }
    public function replies () {

        return $this->hasMany(Reply::class);

    }

}
