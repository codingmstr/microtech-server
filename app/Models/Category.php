<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'company',
        'phone',
        'location',
        'description',
        'notes',
        'image',
        'allow_products',
        'allow_orders',
        'allow_coupons',
        'active',
    ];

    public function products () {

        return $this->hasMany(Product::class);

    }

}
