<?php

namespace App\Http\Controllers\payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Transaction;
use App\Models\User;

class CryptoController extends Controller {

    protected $client;
    protected $apiKey;
    protected $baseUrl;
    protected $callback_url;

    public function __construct () {

        $this->client = new Client();
        $this->apiKey = '70bc6866212dfe461689fc7cc042774f87341f32';
        $this->baseUrl = 'https://api.cryptomus.com/v1/';
        // $this->callback_url = url('/') . '/api/client/webhook/paytabs';
        $this->callback_url = 'https://c0c4-102-184-124-126.ngrok-free.app/api/client/webhook/paytabs';

    }
    public function index ( Request $req ) {

        return $this->success(['url' => 'https://cryptomus.com']);

    }
    public function is_valid ( $req ) {
        
        return true;

    }
    public function callback ( Request $req ) {

        return true;

    }

}
