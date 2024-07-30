<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'company',
        'phone',
        'country',
        'city',
        'location',
        'old_price',
        'new_price',
        'description',
        'details',
        'includes',
        'notes',
        'rate',
        'allow_orders',
        'allow_coupons',
        'allow_reviews',
        'active',
    ];
    protected $casts = [
        'includes' => 'json'
    ];

    public function category () {

        return $this->belongsTo(Category::class);

    }
    public function orders () {

        return $this->hasMany(Order::class);

    }
    public function reviews () {

        return $this->hasMany(Review::class);

    }

}
