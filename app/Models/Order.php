<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'coupon_id',
        'name',
        'email',
        'phone',
        'address',
        'country',
        'city',
        'notes',
        'secret_key',
        'price',
        'coupon_discount',
        'coupon_code',
        'paid',
        'status',
        'active',
        'paid_at',
        'confirmed_at',
        'cancelled_at',
        'ordered_at',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'ordered_at' => 'datetime',
    ];

    public function user () {

        return $this->belongsTo(User::class);

    }
    public function product () {

        return $this->belongsTo(Product::class);

    }
    public function coupon () {

        return $this->belongsTo(Coupon::class);

    }
    public function review () {

        return $this->hasMany(Review::class);

    }

}
