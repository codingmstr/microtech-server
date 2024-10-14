<?php

namespace App\Http\Controllers\payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymobController extends Controller {

    protected $client;
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;
    protected $hmacSecret;
    protected $apiBaseUrl;

    public function __construct () {

        $this->client = new Client();
        $this->integrationId = env('PAYMOB_INTEGRATION_ID');
        $this->iframeId = env('PAYMOB_IFRAME_ID');
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->apiBaseUrl = env('PAYMOB_API_BASE_URL');
        $this->hmacSecret = env('PAYMOB_HMAC_SECRET');

    }
    public function check_hmac ( $data ) {

        $amount_cents = $data->amount_cents;
        $created_at = $data->created_at;
        $currency = $data->currency;
        $error_occured = $this->bool_string($data->error_occured);
        $has_parent_transaction = $this->bool_string($data->has_parent_transaction);
        $id = $data->id;
        $integration_id = $data->integration_id;
        $is_3d_secure = $this->bool_string($data->is_3d_secure);
        $is_auth = $this->bool_string($data->is_auth);
        $is_capture = $this->bool_string($data->is_capture);
        $is_refunded = $this->bool_string($data->is_refunded);
        $is_standalone_payment = $this->bool_string($data->is_standalone_payment);
        $is_voided = $this->bool_string($data->is_voided);
        $order_id = $data->order;
        $owner = $data->owner;
        $pending = $this->bool_string($data->pending);
        $source_data_pan = $data->source_data_pan;
        $source_data_sub_type = $data->source_data_sub_type;
        $source_data_type = $data->source_data_type;
        $success = $this->bool_string($data->success);

        $key = $amount_cents . $created_at . $currency . $error_occured . $has_parent_transaction . $id .
                $integration_id . $is_3d_secure . $is_auth . $is_capture . $is_refunded . $is_standalone_payment . $is_voided .
                $order_id . $owner . $pending . $source_data_pan . $source_data_sub_type . $source_data_type . $success;

        $key = hash_hmac('SHA512', $key, $this->hmacSecret);
        return $key === $data->hmac;

    }
    public function create ( Request $req ) {

        $response = $this->client->post($this->apiBaseUrl . 'auth/tokens', ['json' => ['api_key' => $this->apiKey]]);
        $token = json_decode($response->getBody(), true)['token'];

        $response = $this->client->post($this->apiBaseUrl . 'ecommerce/orders', [
            'json' => [
                'auth_token' => $token,
                'delivery_needed' => false,
                'amount_cents' => ceil($this->float($req->amount) * 100 * 48.59),
                'currency' => 'EGP',
                'merchant_order_id' => uniqid(),
                'items' => [],
            ]
        ]);
        $merchant= json_decode($response->getBody(), true);

        $response = $this->client->post($this->apiBaseUrl . 'acceptance/payment_keys', [
            'json' => [
                'auth_token' => $token,
                'order_id' => $merchant['id'],
                'amount_cents' => $merchant['amount_cents'],
                'currency' => $merchant['currency'],
                'expiration' => 3600,
                'integration_id' => $this->integrationId,
                'billing_data' => [
                    'first_name' => 'Coding',
                    'last_name' => 'Master',
                    'email' => 'codingmaster009@gmail.com',
                    'phone_number' => '01221083507',
                    'country' => 'EGY',
                    'city' => 'Banha',
                    'state' => 'Qalubia',
                    'street' => 'city star',
                    'postal_code' => '13511',
                    'floor' => 'NA',
                    'building' => 'NA',
                    'apartment' => 'NA',
                    'shipping_method' => 'NA',
                ],
            ]
        ]);
        $payment_token = json_decode($response->getBody(), true)['token'];

        $url = "https://accept.paymobsolutions.com/api/acceptance/iframes/{$this->iframeId}?payment_token=$payment_token";
        return $this->success(['url' => $url]);

    }
    public function verify ( Request $req ) {

        if ( !self::check_hmac($req) ) return $this->failed();
        // if ( $this->bool($req->error_occured) || !$this->bool($req->success) ) return $this->failed();

        $data = [
            'transaction_id' => $req->id,
            'currency' => $req->currency,
            'amount' => round($req->amount_cents / 100 / 48.59, 2),
            'payment' => 'paymob',
            'method' => 'visa',
        ];

        return $this->deposit( $req, $data );

    }

}
