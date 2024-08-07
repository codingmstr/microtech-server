<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VendorResource;
use App\Models\User;
use App\Models\File;
use App\Models\Product;
use App\Models\Order;
use App\Models\Coupon;

class VendorController extends Controller {

    public function statistics ( $id ) {

        $products = $this->charts( Product::where('vendor_id', $id) );
        $orders = $this->charts( Order::where('vendor_id', $id) );
        $coupons = $this->charts( Coupon::where('vendor_id', $id) );
        return ['products' => $products, 'orders' => $orders, 'coupons' => $coupons];

    }
    public function index ( Request $req ) {

        $data = $this->paginate( User::where('role', 2), $req );
        $items = VendorResource::collection( $data['items'] );
        return $this->success(['items' => $items, 'total' => $data['total']]);

    }
    public function show ( Request $req, User $user ) {

        if ( $user->role != 2 ) return $this->failed(['vendor' => 'not exists']);
        $item = VendorResource::make( $user );
        return $this->success(['item' => $item, 'statistics' => $this->statistics($user->id)]);

    }
    public function store ( Request $req ) {

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'max:255'],
        ]);
        if ( $validator->fails() ) {

            return $this->failed($validator->errors());

        }
        $data = [
            'admin_id' => $this->user()->id,
            'role' => 2,
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'age' => $this->float($req->age),
            'phone' => $req->phone,
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'notes' => $req->notes,
            'ip' => $req->ip(),
            'agent' => $req->userAgent(),
            'allow_categories' => $this->bool($req->allow_categories),
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_blogs' => $this->bool($req->allow_blogs),
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_replies' => $this->bool($req->allow_replies),
            'allow_reports' => $this->bool($req->allow_reports),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_contacts' => $this->bool($req->allow_contacts),
            'allow_statistics' => $this->bool($req->allow_statistics),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_login' => $this->bool($req->allow_login),
            'active' => $this->bool($req->active),
        ];

        $user = User::create($data);
        $this->upload_files([$req->file('image_file')], 'user', $user->id);
        return $this->success();

    }
    public function update ( Request $req, User $user ) {

        if ( $user->role != 2 ) return $this->failed(['vendor' => 'not exists']);

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);
        if ( $validator->fails() ) {

            return $this->failed($validator->errors());

        }
        if ( $req->file('image_file') ) {

            $file_id = File::where('table', 'user')->where('column', $user->id)->first()?->id;
            $this->delete_files([$file_id], 'user');
            $this->upload_files([$req->file('image_file')], 'user', $user->id);

        }
        if ( $req->password ) {

            $user->password = Hash::make($req->password);
            
        }
        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'age' => $this->float($req->age),
            'phone' => $req->phone,
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'notes' => $req->notes,
            'ip' => $req->ip(),
            'agent' => $req->userAgent(),
            'allow_categories' => $this->bool($req->allow_categories),
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_blogs' => $this->bool($req->allow_blogs),
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_replies' => $this->bool($req->allow_replies),
            'allow_reports' => $this->bool($req->allow_reports),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_contacts' => $this->bool($req->allow_contacts),
            'allow_statistics' => $this->bool($req->allow_statistics),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_login' => $this->bool($req->allow_login),
            'active' => $this->bool($req->active),
        ];

        $user->update($data);
        return $this->success();

    }
    public function delete ( Request $req, User $user ) {

        if ( $user->role != 2 ) return $this->failed(['vendor' => 'not exists']);
        $user->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) User::where('id', $id)->where('role', 2)->delete();
        return $this->success();

    }

}
