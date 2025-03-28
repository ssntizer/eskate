<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;
use Exception;

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
        $context = stream_context_create([
            "http" => [
                "header" => "Authorization: Basic " . base64_encode("{$this->clientId}:{$this->clientSecret}\r\n") .
                            "Content-Type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => "grant_type=client_credentials"
            ]
        ]);

        $response = file_get_contents('https://api-m.sandbox.paypal.com/v1/oauth2/token', false, $context);
        return json_decode($response)->access_token ?? null;
    }

    public function createOrder()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $amount = $input['amount'] ?? '10.00';

            $accessToken = $this->getAccessToken();
            if (!$accessToken) throw new Exception('Error de autenticación');

            $data = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amount
                        ]
                    ]
                ]
            ];

            $context = stream_context_create([
                "http" => [
                    "header" => "Authorization: Bearer $accessToken\r\n" .
                                "Content-Type: application/json\r\n",
                    "method" => "POST",
                    "content" => json_encode($data)
                ]
            ]);

            $response = file_get_contents('https://api-m.sandbox.paypal.com/v2/checkout/orders', false, $context);
            return $this->response->setJSON(json_decode($response, true));

        } catch (Exception $e) {
            return $this->response->setJSON([
                "error" => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function captureOrder()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $orderID = $input['orderID'] ?? null;

            if (!$orderID) throw new Exception('Order ID requerido');

            $accessToken = $this->getAccessToken();
            if (!$accessToken) throw new Exception('Error de autenticación');

            $context = stream_context_create([
                "http" => [
                    "header" => "Authorization: Bearer $accessToken\r\n" .
                                "Content-Type: application/json\r\n",
                    "method" => "POST"
                ]
            ]);

            $response = file_get_contents("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderID}/capture", false, $context);
            $result = json_decode($response, true);

            // Enviar email
            if (isset($result['payer']['email_address'])) {
                $this->sendConfirmationEmail(
                    $result['payer']['email_address'],
                    $result['purchase_units'][0]['amount']['value']
                );
            }

            return $this->response->setJSON($result);

        } catch (Exception $e) {
            return $this->response->setJSON([
                "error" => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    private function sendConfirmationEmail($email, $amount)
    {
        $mail = \Config\Services::email();
        
        $mail->setTo($email);
        $mail->setSubject('Confirmación de compra');
        $mail->setMessage("
            <h1>¡Gracias por tu compra!</h1>
            <p>Monto: $amount USD</p>
            <p>Tu pedido está siendo procesado.</p>
        ");
        
        $mail->send();
    }
}