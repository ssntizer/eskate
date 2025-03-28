<?php
namespace App\Controllers;

use App\Models\CompraModel;
use CodeIgniter\Controller;
use Config\Services;
use Exception;

class PaypalController extends Controller
{
    protected $compraModel;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->compraModel = new CompraModel();
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->clientSecret = getenv('PAYPAL_CLIENT_SECRET');
    }

    protected function getHttpClient()
    {
        return Services::curlrequest([
            'base_uri' => 'https://api.sandbox.paypal.com',
            'timeout' => 45,  // Aumentado a 45 segundos
            'verify' => false,
            'http_errors' => false,
            'headers' => [
    'Accept' => 'application/json',
    'Accept-Language' => 'en_US',
    'Cache-Control' => 'no-cache', // Importante para Render
    'Connection' => 'keep-alive'
]
        ]);
    }

    private function getAccessToken()
    {
        $client = $this->getHttpClient();
        
        try {
            $response = $client->post('/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => ['grant_type' => 'client_credentials']
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);

            if ($response->getStatusCode() !== 200) {
                log_message('error', "PayPal Token Error: {$body}");
                return null;
            }

            return $data['access_token'] ?? null;

        } catch (Exception $e) {
            log_message('error', "PayPal Token Exception: {$e->getMessage()}");
            return null;
        }
    }

    public function ejecutarPago()
    {
        // Verificación completa de parámetros
        $requiredParams = ['paymentId', 'PayerID', 'email', 'address_id'];
        foreach ($requiredParams as $param) {
            if (empty($this->request->getGet($param))) {
                log_message('error', "Falta parámetro: {$param}");
                return redirect()->to('/compra/error')->with('error', 'Datos incompletos');
            }
        }

        $paymentId = $this->request->getGet('paymentId');
        $payerId = $this->request->getGet('PayerID');
        $email = $this->request->getGet('email');
        $addressId = $this->request->getGet('address_id');

        // Obtener token con reintento
        $accessToken = $this->getAccessTokenWithRetry();

        if (!$accessToken) {
            return redirect()->to('/compra/error')->with('error', 'Error de conexión con PayPal');
        }

        $client = $this->getHttpClient();
        
        try {
            // Ejecutar pago con verificación extendida
            $response = $client->post("/v1/payments/payment/{$paymentId}/execute", [
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => uniqid()
                ],
                'body' => json_encode(['payer_id' => $payerId])
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);

            // Verificación exhaustiva de la respuesta
            if ($response->getStatusCode() !== 200) {
                log_message('error', "PayPal Execute Error - Status: {$response->getStatusCode()}, Body: {$body}");
                return redirect()->to('/compra/error')->with('error', 'Error al procesar pago');
            }

            if (!isset($data['state']) || $data['state'] !== 'approved') {
                log_message('error', "Pago no aprobado: " . print_r($data, true));
                return redirect()->to('/compra/error')->with('error', 'Pago no aprobado');
            }

            // Registrar compra con verificación
            $compraData = [
                'user_id' => session()->get('user_id') ?? 0,
                'email' => $email,
                'address_id' => $addressId,
                'monto' => $data['transactions'][0]['amount']['total'] ?? 0,
                'metodo_pago' => 'paypal',
                'status' => 'completado',
                'paypal_order_id' => $paymentId,
                'paypal_payer_id' => $payerId,
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'paypal_response' => json_encode($data)  // Guardar respuesta completa
            ];

            if (!$this->compraModel->registrarCompra($compraData)) {
                log_message('error', "Error al guardar compra: " . print_r($compraData, true));
                // Aún así redirigir a éxito porque el pago sí se completó
            }

            return view('completada', [
                'success' => 'Pago completado',
                'paypal_data' => $data
            ]);

        } catch (Exception $e) {
            log_message('error', "PayPal Exception: {$e->getMessage()}\n".$e->getTraceAsString());
            return redirect()->to('/compra/error')->with('error', 'Error al procesar pago');
        }
    }

    private function getAccessTokenWithRetry($maxRetries = 2)
    {
        $retry = 0;
        do {
            $token = $this->getAccessToken();
            if ($token) return $token;
            $retry++;
            sleep(1); // Esperar 1 segundo entre reintentos
        } while ($retry < $maxRetries);
        
        return null;
    }
    public function showSuccessPage()
{
    return view('completada');
}

public function showErrorPage()
{
    return view('error_compra');
}
}