<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AdminResource;
use Illuminate\Support\Facades\Hash;
use App\Models\File;

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

            $file_id = File::where('table', 'user')->where('column', $user->id)->first()?->id;
            $this->delete_files([$file_id], 'user');
            $this->upload_files([$req->file('image_file')], 'user', $user->id);

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
        $user = AdminResource::make( $user );
        return $this->success(['user' => $user]);

    }
    public function password ( Request $req ) {

        $user = $this->user();

        if ( !Hash::check($req->old_password, $user->password) ) {
            return $this->failed(['password' => 'not correct']);
        }

        $user->update(['password' => Hash::make($req->password)]);
        return $this->success();

    }

}
