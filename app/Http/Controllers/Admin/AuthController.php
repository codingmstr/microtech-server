<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\AdminResource;

class AuthController extends Controller {

    public function login ( Request $req ) {

        $user = User::where('email', $req->email)->first();

        if ( !$user ) {

            return $this->failed(['email' => 'invalid email']);

        }
        if ( !Hash::check($req->password, $user->password) ) {

            return $this->failed(['password' => 'invalid password']);

        }
        if ( $user->role != 1 || !$user->allow_login ) {

            return $this->failed(['permission' => 'access denied']);

        }

        $token = $user->createToken($req->userAgent())->plainTextToken;
        $user = AdminResource::make($user);

        return $this->success(['user' => $user, 'token' => $token]);

    }
    public function unlock ( Request $req ) {

        if ( !Hash::check($req->password, $this->user()->password) ) {

            return $this->failed(['password' => 'invalid password']);

        }

        self::logout($req);
        $token = $this->user()->createToken($req->userAgent())->plainTextToken;
        $user = AdminResource::make($this->user());

        return $this->success(['user' => $user, 'token' => $token]);

    }
    public function logout ( Request $req ) {

        $this->user()->currentAccessToken()->delete();
        return $this->success();

    }

}
