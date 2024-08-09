<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Review;
use App\Models\Contact;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\Report;
use App\Models\Mail;
use App\Models\Relation;
use App\Models\Message;
use App\Models\User;

class SettingController extends Controller {

    public function index ( Request $req ) {

        $settings = SettingResource::make( Setting::find(1) );
        return $this->success(['settings' => $settings]);

    }
    public function update ( Request $req ) {

        $setting = Setting::find(1);

        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'language' => $req->language,
            'facebook' => $req->facebook,
            'whatsapp' => $req->whatsapp,
            'youtube' => $req->youtube,
            'linkedin' => $req->linkedin,
            'instagram' => $req->instagram,
            'telegram' => $req->telegram,
            'twitter' => $req->twitter,
        ];

        $setting->update($data);
        $this->report($req, 'setting', 0, 'update', 'admin');

        return $this->success();

    }
    public function option ( Request $req ) {

        $setting = Setting::find(1);

        $data = [
            'allow_mails' => $this->bool($req->allow_mails),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_registerations' => $this->bool($req->allow_registerations),
            'allow_logins' => $this->bool($req->allow_logins),
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_blogs' => $this->bool($req->allow_blogs),
            'allow_contacts' => $this->bool($req->allow_contacts),
            'allow_emails' => $this->bool($req->allow_emails),
            'allow_notifications' => $this->bool($req->allow_notifications),
            'theme' => $req->theme,
            'running' => $this->bool($req->running),
        ];

        $setting->update($data);
        $this->report($req, 'setting', 0, 'update', 'admin');

        return $this->success();

    }
    public function delete ( Request $req ) {

        $table = trim(strtolower($req->item));

        if ( $table == 'categories' ) { Category::query()->delete(); }
        if ( $table == 'products' ) { Product::query()->delete(); }
        if ( $table == 'coupons' ) { Coupon::query()->delete(); }
        if ( $table == 'orders' ) { Order::query()->delete(); }
        if ( $table == 'reviews' ) { Review::query()->delete(); }
        if ( $table == 'contacts' ) { Contact::query()->delete(); }
        if ( $table == 'blogs' ) { Blog::query()->delete(); }
        if ( $table == 'comments' ) { Comment::query()->delete(); }
        if ( $table == 'replies' ) { Reply::query()->delete(); }
        if ( $table == 'reports' ) { Report::query()->delete(); }
        if ( $table == 'mails' ) { Mail::query()->delete(); }
        if ( $table == 'messages' ) { Relation::query()->delete(); Message::query()->delete(); }
        if ( $table == 'clients' ) { User::where('role', 3)->delete(); }
        if ( $table == 'vendors' ) { User::where('role', 2)->delete(); }
        if ( $table == 'admins' ) { User::where('role', 1)->where('supervisor', false)->delete(); }
        if ( $table == 'supervisors' ) { User::where('role', 1)->where('super', false)->where('supervisor', true)->delete(); }

        $this->report($req, $table, 0, 'delete', 'admin');

        return $this->success();

    }

}
