<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ReplyResource;
use App\Models\User;
use App\Models\File;

class ClientController extends Controller {

    public function index ( Request $req ) {

        $user = ClientResource::collection( User::where('role', 3)->get() );
        return $this->success(['clients' => $user]);

    }
    public function show ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);
        $user = ClientResource::make( $user );
        return $this->success(['client' => $user]);

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
            'role' => 3,
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'language' => $req->language,
            'age' => $this->float($req->age),
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'location' => $req->location,
            'ip' => $req->ip(),
            'agent' => $req->userAgent(),
            'notes' => $req->notes ?? '',
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_contacts' => $this->bool($req->allow_contacts),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_likes' => $this->bool($req->allow_likes),
            'allow_dislikes' => $this->bool($req->allow_dislikes),
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_replies' => $this->bool($req->allow_replies),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_login' => $this->bool($req->allow_login),
            'active' => $this->bool($req->active),
        ];

        $user = User::create($data);
        $this->upload_files([$req->file('image_file')], 'user', $user->id);
        return $this->success();

    }
    public function update ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);

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
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_contacts' => $this->bool($req->allow_contacts),
            'allow_reviews' => $this->bool($req->allow_reviews),
            'allow_likes' => $this->bool($req->allow_likes),
            'allow_dislikes' => $this->bool($req->allow_dislikes),
            'allow_comments' => $this->bool($req->allow_comments),
            'allow_replies' => $this->bool($req->allow_replies),
            'allow_orders' => $this->bool($req->allow_orders),
            'allow_coupons' => $this->bool($req->allow_coupons),
            'allow_login' => $this->bool($req->allow_login),
            'active' => $this->bool($req->active),
        ];

        $user->update($data);
        return $this->success();

    }
    public function delete ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);
        $user->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) User::where('id', $id)->where('role', 3)->delete();
        return $this->success();

    }
    public function orders ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);
        $orders = OrderResource::for_user( $user->orders );
        return $this->success(['orders' => $orders]);

    }
    public function reviews ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);
        $reviews = ReviewResource::for_user( $user->reviews );
        return $this->success(['reviews' => $reviews]);

    }
    public function comments ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);
        $comments = CommentResource::for_user( $user->comments );
        return $this->success(['comments' => $comments]);

    }
    public function replies ( Request $req, User $user ) {

        if ( $user->role != 3 ) return $this->failed(['client' => 'not exists']);
        $replies = ReplyResource::for_user( $user->replies );
        return $this->success(['replies' => $replies]);

    }

}
