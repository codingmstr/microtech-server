<?php

namespace App\Http\Controllers\payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PaypalProvider;
use App\Models\Transaction;
use App\Models\User;

class PaypalController extends Controller {

    protected $provider;
    protected $webhook_id;

    public function __construct () {

        $provider = new PaypalProvider;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $this->provider = $provider;
        $this->webhook_id = config('paypal.webhook_id');

    }
    public function index ( Request $req ) {

        $response = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => $req->redirect_url,
                "cancel_url" => $req->cancel_url,
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "value" => $req->amount,
                        "currency_code" => "USD",
                    ],
                ],
            ],
        ]);

        Transaction::create(['user_id' => $this->user()->id, 'transaction_id' => $response['id'], 'payment' => 'paypal']);
        return $this->success(['url' => $response['links'][1]['href']]);

    }
    public function is_valid ( $req ) {
        
        $data = [
            'auth_algo' => $req->header('PayPal-Auth-Algo'),
            'cert_url' => $req->header('PayPal-Cert-Url'),
            'transmission_id' => $req->header('PayPal-Transmission-Id'),
            'transmission_sig' => $req->header('PayPal-Transmission-Sig'),
            'transmission_time' => $req->header('PayPal-Transmission-Time'),
            'webhook_id' => $this->webhook_id,
            'webhook_event' => $req->all(),
        ];

        $response = $this->provider->verifyWebHook($data);
        if ( $response['verification_status'] === 'SUCCESS' ) return true;
        return false;

    }
    public function callback ( Request $req ) {

        if ( !self::is_valid( $req ) ) return;
        $response = $this->provider->capturePaymentOrder( $req->resource['id'] );
        
        $data = [
            'transaction_id' => $response['id'],
            'currency' => $response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['currency_code'],
            'amount' => $response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value'],
            'completed' => $response['status'] === 'COMPLETED',
            'payment' => 'paypal',
            'method' => 'wallet',
        ];

        $this->transaction( $data );

    }

}