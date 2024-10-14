<?php

namespace App\Http\Controllers\payment;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PaypalProvider;

class PaypalController extends Controller {

    protected $provider;

    public function __construct () {

        $provider = new PaypalProvider;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $this->provider = $provider;

    }
    public function create ( Request $req ) {

        $response = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => $req->redirect_url,
                "cancel_url" => $req->callback_url,
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $req->currency,
                        "value" => $req->amount,
                    ],
                ],
            ],
        ]);

        $url = $response['links'][1]['href'];
        return $this->success(['url' => $url]);

    }
    public function verify ( Request $req ) {

        $response = $this->provider->capturePaymentOrder( $req->token );

        if ( !isset($response['status']) || $response['status'] !== 'COMPLETED' ) return $this->failed();
        if ( !$response['purchase_units'][0]['payments']['captures'][0]['final_capture'] ) return $this->failed();

        $data = [
            'transaction_id' => $response['id'],
            'currency' => $response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['currency_code'],
            'amount' => $response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value'],
            'payment' => 'paypal',
            'method' => 'wallet'
        ];

        return $this->deposit( $req, $data );

    }

}
