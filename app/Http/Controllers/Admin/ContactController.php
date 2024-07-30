<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController extends Controller {

    public function index ( Request $req ) {

        $contacts = ContactResource::collection( Contact::all() );
        return $this->success(['contacts' => $contacts]);

    }
    public function delete ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Contact::find($id)?->delete();
        return $this->success();

    }

}
