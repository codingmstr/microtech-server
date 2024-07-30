<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'content',
        'rate',
        'active',
    ];

    public function user () {

        return $this->belongsTo(User::class);

    }
    public function product () {

        return $this->belongsTo(Product::class);

    }
    public function order () {

        return $this->belongsTo(Order::class);

    }

}
