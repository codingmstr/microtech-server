<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use App\Models\User;
use App\Models\File;

class AdminController extends Controller {

    public function index ( Request $req ) {

        $users = User::where('role', 1)->where('super', false)->where('id', '!=', $this->user()->id);
        if ( !$this->user()->super ) $users = $users->where('admin_id', $this->user()->id);

        $data = $this->paginate( $users, $req );
        $items = AdminResource::collection( $data['items'] );
        return $this->success(['items' => $items, 'total' => $data['total']]);

    }
    public function show ( Request $req, User $user ) {

        if ( $user->role != 1 || $user->super || $user->id == $this->user()->id ) return $this->failed(['admin' => 'not exists']);
        if ( !$this->user()->super && $user->admin_id != $this->user()->id ) return $this->failed(['admin' => 'not exists']);

        $item = AdminResource::make( $user );
        return $this->success(['item' => $item]);

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
            'role' => 1,
            'admin_id' => $this->user()->id,
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'language' => $req->language,
            'age' => $this->float($req->age),
            'salary' => $this->float($req->salary),
            'phone' => $req->phone,
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
            'allow_clients' => $this->bool($req->allow_clients),
            'allow_vendors' => $this->bool($req->allow_vendors),
            'allow_statistics' => $this->bool($req->allow_statistics),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_mails' => $this->bool($req->allow_mails),
            'allow_login' => $this->bool($req->allow_login),
            'supervisor' => $this->bool($req->supervisor),
            'active' => $this->bool($req->active),
        ];

        $user = User::create($data);
        $this->upload_files([$req->file('image_file')], 'user', $user->id);
        return $this->success();

    }
    public function update ( Request $req, User $user ) {

        if ( $user->role != 1 || $user->super || $user->id == $this->user()->id ) return $this->failed(['admin' => 'not exists']);
        if ( !$this->user()->super && $user->admin_id != $this->user()->id ) return $this->failed(['admin' => 'not exists']);

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
            'language' => $req->language,
            'age' => $this->float($req->age),
            'salary' => $this->float($req->salary),
            'phone' => $req->phone,
            'country' => $req->country,
            'city' => $req->city,
            'street' => $req->street,
            'notes' => $req->notes,
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
            'allow_clients' => $this->bool($req->allow_clients),
            'allow_vendors' => $this->bool($req->allow_vendors),
            'allow_statistics' => $this->bool($req->allow_statistics),
            'allow_messages' => $this->bool($req->allow_messages),
            'allow_mails' => $this->bool($req->allow_mails),
            'allow_login' => $this->bool($req->allow_login),
            'supervisor' => $this->bool($req->supervisor),
            'active' => $this->bool($req->active),
        ];

        $user->update($data);
        return $this->success();

    }
    public function delete ( Request $req, User $user ) {

        if ( $user->role != 1 || $user->super || $user->id == $this->user()->id ) return $this->failed(['admin' => 'not exists']);
        if ( !$this->user()->super && $user->admin_id != $this->user()->id ) return $this->failed(['admin' => 'not exists']);

        $user->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) {

            $user = User::where('id', $id)->where('id', '!=', $this->user()->id)->where('super', false);
            if ( !$this->user()->super ) $user->where('admin_id', $this->user()->id);
            $user->delete();

        }

        return $this->success();

    }

}
