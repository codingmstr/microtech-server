<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use App\Models\Report;

class ReportController extends Controller {

    public function index ( Request $req ) {

        $data = $this->paginate( Report::query(), $req );
        $items = ReportResource::collection( $data['items'] );
        return $this->success(['items' => $items, 'total'=> $data['total']]);

    }
    public function show ( Request $req, Report $report ) {

        $item = ReportResource::make( $report );
        return $this->success(['item' => $item]);

    }
    public function delete ( Request $req, Report $report ) {

        $report->delete();
        return $this->success();

    }
    public function delete_group ( Request $req ) {

        foreach ( $this->parse($req->ids) as $id ) Report::find($id)?->delete();
        return $this->success();

    }

}
