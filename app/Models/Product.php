<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'vendor_id',
        'category_id',
        'name',
        'company',
        'phone',
        'language',
        'country',
        'city',
        'street',
        'location',
        'old_price',
        'new_price',
        'description',
        'details',
        'availability',
        'policy',
        'rules',
        'safety',
        'includes',
        'notes',
        'rate',
        'allow_reviews',
        'allow_orders',
        'allow_coupons',
        'active',
    ];
    protected $casts = [
        'includes' => 'json'
    ];

    public function admin () {

        return $this->belongsTo(User::class, 'admin_id');

    }
    public function vendor () {

        return $this->belongsTo(User::class, 'vendor_id');

    }
    public function category () {

        return $this->belongsTo(Category::class);

    }
    public function reviews () {

        return $this->hasMany(Review::class);

    }
    public function orders () {

        return $this->hasMany(Order::class);

    }
    public function coupons () {

        return $this->hasMany(Coupon::class);

    }

}
