<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'city',
        'location',
        'language',
        'theme',
        'facebook',
        'whatsapp',
        'telegram',
        'twitter',
        'youtube',
        'instagram',
        'linkedin',
        'balance',
        'profit',
        'income',
        'expenses',
        'withdraws',
        'deposits',
        'allow_mails',
        'allow_chats',
        'allow_registerations',
        'allow_logins',
        'allow_products',
        'allow_coupons',
        'allow_orders',
        'allow_blogs',
        'allow_contacts',
        'allow_emails',
        'allow_notifications',
        'running',
    ];

}
