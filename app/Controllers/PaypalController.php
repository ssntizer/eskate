<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class PayPalController extends Controller
{
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->clientSecret = getenv('PAYPAL_CLIENT_SECRET');
    }

    private function getAccessToken()
    {
        $client = Services::curlrequest();
        
        try {
            $response = $client->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => ['grant_type' => 'client_credentials'],
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en_US'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['access_token'] ?? null;

        } catch (\Exception $e) {
            log_message('error', 'PayPal Token Error: '.$e->getMessage());
            return null;
        }
    }

    public function createOrder()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $amount = $input['amount'] ?? '10.00';

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON(['error' => 'Failed to get access token']);
            }

            $client = Services::curlrequest();
            $response = $client->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                    'Content-Type' => 'application/json',
                    'Prefer' => 'return=representation'
                ],
                'json' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $amount
                        ]
                    ]]
                ],
                'http_errors' => false
            ]);

            return $this->response
                ->setStatusCode($response->getStatusCode())
                ->setJSON(json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            log_message('error', 'Create Order Error: '.$e->getMessage());
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function captureOrder()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $orderID = $input['orderID'] ?? null;

            if (!$orderID) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['error' => 'Order ID is required']);
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON(['error' => 'Failed to get access token']);
            }

            $client = Services::curlrequest();
            $response = $client->post(
                "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderID}/capture",
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.$accessToken,
                        'Content-Type' => 'application/json'
                    ],
                    'http_errors' => false
                ]
            );

            $responseData = json_decode($response->getBody(), true);

            // Registrar la compra en tu base de datos aquí
            // $this->registrarCompra($responseData);

            return $this->response
                ->setStatusCode($response->getStatusCode())
                ->setJSON($responseData);

        } catch (\Exception $e) {
            log_message('error', 'Capture Order Error: '.$e->getMessage());
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
}