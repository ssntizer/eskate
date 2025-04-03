<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class PayPalController extends Controller
{
    private $clientId = "AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ";
    private $clientSecret = "ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A";
    private $environment = "sandbox";

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
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $credentials",
            "Content-Type: application/x-www-form-urlencoded"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpCode !== 200) {
            log_message('error', 'PayPal Token Error: ' . $response);
            return null;
        }
    
        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    public function createOrder()
    {
        $input = $this->request->getJSON();
        $amount = $input->amount ?? "10.00";
        $currency = $input->currency ?? "USD";

        if (!is_numeric($amount)) {
            return $this->response->setJSON([
                'error' => 'Invalid amount',
                'message' => 'Amount must be a number'
            ])->setStatusCode(400);
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return $this->response->setJSON([
                'error' => 'Authentication failed',
                'message' => 'Could not get PayPal access token'
            ])->setStatusCode(500);
        }

        $url = $this->getApiBaseUrl() . "/v2/checkout/orders";
        $body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => $currency,
                    "value" => $amount
                ]
            ]]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json",
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 && $httpCode !== 201) {
            log_message('error', 'PayPal CreateOrder Error: ' . $response);
            return $this->response->setJSON(json_decode($response, true))
                                 ->setStatusCode($httpCode);
        }

        return $this->response->setJSON(json_decode($response, true));
    }

    public function captureOrder($orderID = null)
    {
        if (!$orderID) {
            $input = $this->request->getJSON();
            $orderID = $input->orderID ?? $this->request->getVar('orderID');
        }

        if (!$orderID) {
            return $this->response->setJSON([
                'error' => 'Missing order ID',
                'message' => 'Order ID is required'
            ])->setStatusCode(400);
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return $this->response->setJSON([
                'error' => 'Authentication failed',
                'message' => 'Could not get PayPal access token'
            ])->setStatusCode(500);
        }

        $url = $this->getApiBaseUrl() . "/v2/checkout/orders/$orderID/capture";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $resultData = json_decode($response, true);
        
        if ($httpCode !== 200 && $httpCode !== 201) {
            log_message('error', 'PayPal Capture Error: ' . $response);
            return $this->response->setJSON($resultData)
                                 ->setStatusCode($httpCode);
        }

        // Enviar email si existe dirección
        if (isset($resultData['payer']['email_address'])) {
            $email = $resultData['payer']['email_address'];
            $this->sendConfirmationEmail($email, $orderID);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $resultData
        ]);
    }

    private function sendConfirmationEmail($email, $orderID)
    {
        try {
            $emailService = Services::email();
            
            $emailService->setTo($email);
            $emailService->setFrom('no-reply@irconnect.com', 'IRConnect');
            $emailService->setSubject('¡Gracias por comprar IRConnect!');
            $emailService->setMessage("
                <h1>Su compra ha sido cargada en nuestro sistema</h1>
                <p>Número de orden: $orderID</p>
                <p>Cuando reciba el producto, ya podrá disfrutar de todas las funciones de IRConnect</p>
            ");
            
            $emailService->send();
        } catch (\Exception $e) {
            log_message('error', 'Email sending failed: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Payment completed successfully'
        ]);
    }

    public function cancel()
    {
        return $this->response->setJSON([
            'status' => 'cancelled',
            'message' => 'Payment was cancelled'
        ]);
    }
}