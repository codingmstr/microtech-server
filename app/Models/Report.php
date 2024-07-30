<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Report extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'action_table',
        'action_column',
        'process',
        'ip',
        'agent',
        'location',
        'price',
        'amount',
        'paid',
        'status',
        'active',
    ];

    public function user () {

        return $this->belongsTo(User::class);

    }

}
