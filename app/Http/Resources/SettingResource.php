<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource {

    public function toArray ( Request $req ) {

        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'location' => $this->location,
            'language' => $this->language,
            'theme' => $this->theme,
            'facebook' => $this->facebook,
            'whatsapp' => $this->whatsapp,
            'youtube' => $this->youtube,
            'linkedin' => $this->linkedin,
            'instagram' => $this->instagram,
            'telegram' => $this->telegram,
            'twitter' => $this->twitter,
            'balance' => $this->balance,
            'profit' => $this->profit,
            'income' => $this->income,
            'expenses' => $this->expenses,
            'deposits' => $this->deposits,
            'withdraws' => $this->withdraws,
            'allow_mails' => $this->allow_mails,
            'allow_messages' => $this->allow_messages,
            'allow_registerations' => $this->allow_registerations,
            'allow_logins' => $this->allow_logins,
            'allow_products' => $this->allow_products,
            'allow_coupons' => $this->allow_coupons,
            'allow_orders' => $this->allow_orders,
            'allow_blogs' => $this->allow_blogs,
            'allow_contacts' => $this->allow_contacts,
            'allow_emails' => $this->allow_emails,
            'allow_notifications' => $this->allow_notifications,
            'running' => $this->running,
        ];

    }
}
