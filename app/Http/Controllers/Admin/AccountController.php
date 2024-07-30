<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AdminResource;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller {

    public function index ( Request $req ) {

        $user = AdminResource::make( $this->user() );
        return $this->success(['user' => $user]);

    }
    public function save ( Request $req ) {

        $user = $this->user();

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);
        if ( $validator->fails() ) {

            return $this->failed($validator->errors());

        }
        if ( $req->file('image_file') ) {

            Storage::delete($user->image);
            $user->image = $this->upload_file($req->file('image_file'), 'user');

        }
        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'age' => $this->float($req->age),
            'language' => $req->language,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
        ];

        $user->update($data);
        return $this->success(['user' => $user]);

    }
    public function password ( Request $req ) {

        $user = $this->user();

        $validator = Validator::make($req->all(), [
            'password' => ['required', 'min:6', 'max:255'],
        ]);
        if ( $validator->fails() ) {
            return $this->failed($validator->errors());
        }
        if ( !Hash::check($req->old_password, $user->password) ) {
            return $this->failed(['old_password' => 'not correct']);
        }

        $user->update(['password' => Hash::make($req->password)]);
        return $this->success();

    }

}
