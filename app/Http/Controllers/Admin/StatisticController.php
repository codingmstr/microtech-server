<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StatisticController extends Controller {

    public function index ( Request $req ) {

        $user = Auth::guard('sanctum')->user();

        return response(['status' => true, 'user' => $user]);

    }

}
