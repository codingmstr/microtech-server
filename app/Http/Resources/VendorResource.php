<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\File;

class VendorResource extends JsonResource {

    public function toArray ( Request $req ) {

        $files = File::where('table', 'user')->where('column', $this->id);
        $image = $files->where('type', 'image')->latest()->first()?->url;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => '?',
            'age' => $this->age,
            'language' => $this->language,
            'country' => $this->country,
            'city' => $this->city,
            'ip' => $this->ip,
            'agent' => $this->agent,
            'balance' => $this->balance,
            'notes' => $this->notes,
            'allow_messages' => $this->allow_messages,
            'allow_products' => $this->allow_products,
            'allow_coupons' => $this->allow_coupons,
            'allow_orders' => $this->allow_orders,
            'allow_reports' => $this->allow_reports,
            'allow_reviews' => $this->allow_reviews,
            'allow_statistics' => $this->allow_statistics,
            'allow_login' => $this->allow_login,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'login_at' => $this->login_at?->format('Y-m-d H:i:s'),
            'image' => $image,
            'info' => ['name' => $this->name, 'image' => $image],
        ];

    }

}