<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VendorResource;
use App\Models\User;
use App\Models\File;

class VendorController extends Controller {

    public function index ( Request $req ) {

        $user = VendorResource::collection( User::where('role', 2)->get() );
        return $this->success(['vendors' => $user]);

    }
    public function show ( Request $req, User $user ) {

        if ( $user->role != 2 ) return $this->failed(['vendor' => 'not exists']);
        $user = VendorResource::make( $user );
        return $this->success(['vendor' => $user]);

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
            'role' => 2,
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'language' => $req->language,
            'age' => $this->float($req->age),
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'notes' => $req->notes ?? '',
            'ip' => $req->ip(),
            'agent' => $req->userAgent(),
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_statistics' => $this->bool($req->allow_statistics),
            'allow_reports' => $this->bool($req->allow_reports),
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
            'password' => ['required', 'max:255'],
        ]);
        if ( $validator->fails() ) {

            return $this->failed($validator->errors());

        }
        if ( $req->file('image_file') ) {

            $file_id = File::where('table', 'user')->where('column', $user->id)->first()?->id;
            $this->delete_files([$file_id], 'user');
            $this->upload_files([$req->file('image_file')], 'user', $user->id);

        }
        if ( $req->password && $req->password !== '?' ) {

            $user->password = Hash::make($req->password);

        }
        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'language' => $req->language,
            'age' => $this->float($req->age),
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'notes' => $req->notes ?? '',
            'allow_products' => $this->bool($req->allow_products),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_statistics' => $this->bool($req->allow_statistics),
            'allow_reports' => $this->bool($req->allow_reports),
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
