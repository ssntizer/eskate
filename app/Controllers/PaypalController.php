<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class PayPalController extends Controller
{
    private $clientId = "AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ";
    private $clientSecret ="ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A"; 


    private function getAccessToken()
    {
        $url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
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
            log_message('error', 'Error obteniendo token de PayPal: ' . $response);
            return null;
        }
    
        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    public function createOrder()
    {
        $input = $this->request->getJSON();
        $amount = $input->amount ?? "10.00"; // Monto por defecto si no se envía

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return $this->response->setJSON(["error" => "No se pudo obtener el token"])->setStatusCode(500);
        }

        $url = "https://api-m.sandbox.paypal.com/v2/checkout/orders";
        $body = json_encode([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount
                    ]
                ]
            ]
        ]);

        $options = [
            "http" => [
                "header" => "Authorization: Bearer $accessToken\r\n" .
                            "Content-Type: application/json\r\n",
                "method" => "POST",
                "content" => $body
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $this->response->setJSON(json_decode($result, true));
    }

    // Capturar el pago
    public function captureOrder($orderID = null)
{
    // Si no viene por JSON, intenta obtenerlo de la URL
    if (!$orderID) {
        $input = $this->request->getJSON();
        $orderID = $input->orderID ?? $this->request->getVar('orderID');
    }

    if (!$orderID) {
        return $this->response->setJSON(["error" => "Order ID is required"])->setStatusCode(400);
    }

    $accessToken = $this->getAccessToken();
    if (!$accessToken) {
        return $this->response->setJSON(["error" => "No se pudo obtener el token"])->setStatusCode(500);
    }

    $url = "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture";
    
    // Usar cURL en lugar de file_get_contents para mejor control de errores
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return $this->response->setJSON(["error" => $error])->setStatusCode(500);
    }

    $resultData = json_decode($response, true);
    
    if ($httpCode !== 200 && $httpCode !== 201) {
        return $this->response->setJSON($resultData)->setStatusCode($httpCode);
    }

    // Verificar si existe el email antes de enviar
    if (isset($resultData['payer']['email_address'])) {
        $email = $resultData['payer']['email_address'];
        \Config\Services::sendEmail($email, '¡Gracias por comprar IRConnect!', 
            "<h1>Su compra ha sido cargada en nuestro sistema<br><br>Cuando reciba el producto, ya podrá disfrutar de todas las funciones de IRConnect</h1>");
    }

    return $this->response->setJSON($resultData);
}
}