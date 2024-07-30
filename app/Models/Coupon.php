<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'product_id',
        'user_id',
        'name',
        'discount',
        'notes',
        'uses',
        'active',
    ];

    public function category () {

        return $this->belongsTo(Category::class);

    }
    public function product () {

        return $this->belongsTo(Product::class);

    }
    public function user () {

        return $this->belongsTo(User::class);

    }
    public function orders () {

        return $this->hasMany(Order::class);

    }

}
