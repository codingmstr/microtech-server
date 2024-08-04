<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {

    use HasFactory, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'role',
        'admin_id',
        'name',
        'email',
        'phone',
        'image',
        'password',
        'language',
        'country',
        'city',
        'age',
        'ip',
        'agent',
        'notes',
        'salary',
        'supervisor',
        'allow_categories',
        'allow_products',
        'allow_coupons',
        'allow_orders',
        'allow_blogs',
        'allow_reports',
        'allow_contacts',
        'allow_clients',
        'allow_statistics',
        'allow_chats',
        'allow_mails',
        'allow_reviews',
        'allow_likes',
        'allow_dislikes',
        'allow_comments',
        'allow_replies',
        'allow_login',
        'active',
        'login_at',
        'remember_token',
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function orders () {

        return $this->hasMany(Order::class);

    }
    public function reviews () {

        return $this->hasMany(Review::class);

    }
    public function comments () {

        return $this->hasMany(Comment::class);

    }
    public function replies () {

        return $this->hasMany(Reply::class);

    }

}
