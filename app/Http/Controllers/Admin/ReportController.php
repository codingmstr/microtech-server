<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use App\Models\Report;

class ReportController extends Controller {

    public function index ( Request $req ) {

        $reports = ReportResource::collection( Report::all() );
        return $this->success(['reports' => $reports]);

    }
    public function delete ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Report::find($id)?->delete();
        return $this->success();

    }

}
