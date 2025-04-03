<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class PayPalController extends Controller
{
    private $clientId = "AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ";
    private $clientSecret = "ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A";
    private $environment = "sandbox"; // Cambiar a "live" en producción

    public function __construct()
    {
        helper(['url', 'form']);
    }

    private function getApiBaseUrl()
    {
        return $this->environment === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';
    }

    private function getAccessToken()
    {
        $url = $this->getApiBaseUrl() . "/v1/oauth2/token";
        $credentials = base64_encode("$this->clientId:$this->clientSecret");
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $credentials",
            "Content-Type: application/x-www-form-urlencoded"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
    
        if ($error || $httpCode !== 200) {
            log_message('error', 'PayPal Token Error: ' . ($error ?: $response));
            return null;
        }
    
        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    public function createOrder()
    {
        try {
            $input = $this->request->getJSON();
            if (!$input) {
                throw new \Exception("Invalid input data");
            }

            $amount = $input->amount ?? "10.00";
            $currency = $input->currency ?? "USD";
            
            // Validación del monto
            if (!is_numeric($amount)) {
                return $this->response->setJSON([
                    'error' => 'Invalid amount',
                    'details' => 'Amount must be a numeric value'
                ])->setStatusCode(400);
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception("Failed to get PayPal access token");
            }

            $url = $this->getApiBaseUrl() . "/v2/checkout/orders";
            $body = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => $currency,
                            "value" => $amount
                        ]
                    ]
                ],
                "application_context" => [
                    "brand_name" => "IRConnect",
                    "user_action" => "PAY_NOW",
                    "return_url" => base_url('paypal/success'),
                    "cancel_url" => base_url('paypal/cancel')
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json",
                "Accept: application/json"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception("cURL Error: $error");
            }

            $result = json_decode($response, true);
            
            if ($httpCode !== 200 && $httpCode !== 201) {
                log_message('error', 'PayPal API Error: ' . print_r($result, true));
                return $this->response->setJSON([
                    'error' => 'PayPal API Error',
                    'details' => $result
                ])->setStatusCode($httpCode);
            }
        
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'PayPal CreateOrder Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function captureOrder($orderID = null)
    {
        try {
            if (!$orderID) {
                $input = $this->request->getJSON();
                $orderID = $input->orderID ?? $this->request->getVar('orderID');
            }

            if (!$orderID) {
                return $this->response->setJSON([
                    'error' => 'Validation Error',
                    'message' => 'Order ID is required'
                ])->setStatusCode(400);
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception("Failed to get PayPal access token");
            }

            $url = $this->getApiBaseUrl() . "/v2/checkout/orders/$orderID/capture";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json",
                "Accept: application/json"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception("cURL Error: $error");
            }

            $resultData = json_decode($response, true);
            
            if ($httpCode !== 200 && $httpCode !== 201) {
                log_message('error', 'PayPal Capture Error: ' . print_r($resultData, true));
                return $this->response->setJSON($resultData)->setStatusCode($httpCode);
            }

            // Enviar email de confirmación
            if (isset($resultData['payer']['email_address'])) {
                $email = $resultData['payer']['email_address'];
                $this->sendConfirmationEmail($email);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $resultData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'PayPal CaptureOrder Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    private function sendConfirmationEmail($email)
    {
        try {
            $emailService = Services::email();
            
            $emailService->setTo($email);
            $emailService->setFrom('no-reply@irconnect.com', 'IRConnect');
            $emailService->setSubject('¡Gracias por comprar IRConnect!');
            $emailService->setMessage(view('emails/purchase_confirmation'));
            
            if (!$emailService->send()) {
                log_message('error', 'Email sending failed: ' . $emailService->printDebugger());
            }
        } catch (\Exception $e) {
            log_message('error', 'Email Exception: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('paypal/success');
    }

    public function cancel()
    {
        return view('paypal/cancel');
    }
}