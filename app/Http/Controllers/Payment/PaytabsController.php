<?php

namespace App\Http\Controllers\payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Transaction;
use App\Models\User;

class PaytabsController extends Controller {

    protected $client;
    protected $profileId;
    protected $serverKey;
    protected $baseUrl;
    protected $callback_url;

    public function __construct () {

        $this->client = new Client();
        $this->profileId = 138225;
        $this->serverKey = 'SKJ9NDJRD2-JJL9HGTLBM-ZGKRDR6BDR';
        $this->baseUrl = 'https://secure-egypt.paytabs.com/';
        $this->callback_url = url('/') . '/api/client/webhook/paytabs';

    }
    public function index ( Request $req ) {

        $data = [
            'profile_id' => $this->profileId,
            "cart_id"=> uniqid(),
            'cart_amount' => $req->amount,
            "return"=> $req->redirect_url,
            "callback"=> $this->callback_url,
            "cart_currency"=> "USD",
            "paypage_lang"=> "en",
            "tran_type"=> "sale",
            "tran_class"=> "ecom",
            "cart_description"=> "cart description ...",
            "customer_details"=> [
                "name"=> "Coding Master",
                "email"=> "codingmaster@gmail.com",
                "phone"=> "01099188572",
                "street1"=> "talat harb",
                "city"=> "cairo",
                "state"=> "qalubia",
                "country"=> "EG",
                "zip"=> "13713"
            ],
            "shipping_details"=> [
                "name"=> "product name",
                "email"=> "codingmaster@gmail.com",
                "phone"=> "01099188572",
                "street1"=> "talat harb",
                "city"=> "cairo",
                "state"=> "qalubia",
                "country"=> "EG",
                "zip"=> "13713"
            ],
        ];
        $response = $this->client->post("{$this->baseUrl}/payment/request", [
            'json' => $data,
            'headers' => ['authorization' => $this->serverKey],
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        
        Transaction::create(['user_id' => $this->user()->id, 'transaction_id' => $response['tran_ref'], 'payment' => 'paytabs']);
        return $this->success(['url' => $response['redirect_url'], 'transaction_id' => $response['tran_ref']]);

    }
    public function is_valid ( $req ) {
        
        $response = $this->client->post("{$this->baseUrl}/payment/query", [
            'json' => ["profile_id" => $this->profileId, 'tran_ref' => $req->tran_ref],
            'headers' => ['authorization' => $this->serverKey],
        ]);

        return json_decode($response->getBody()->getContents(), true);

    }
    public function callback ( Request $req ) {

        $response = self::is_valid( $req );
        if ( !$response ) return;
        
        $data = [
            'transaction_id' => $response['tran_ref'],
            'currency' => $response['cart_currency'],
            'amount' => $response['cart_amount'],
            'completed' => $response['payment_result']['response_status'] === 'A',
            'payment' => 'paytabs',
            'method' => 'visa',
        ];

        $this->transaction( $data );

    }

}
