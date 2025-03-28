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
        
        // Verificación explícita de credenciales
        if (empty($this->clientId) || empty($this->clientSecret)) {
            die('Credenciales de PayPal no configuradas. Verifica tu archivo .env');
        }
    }

    protected function getHttpClient()
    {
        return Services::curlrequest([
            'base_uri' => 'https://api.sandbox.paypal.com',
            'timeout' => 60,  // Aumentado a 60 segundos para Render
            'verify' => false, // Crucial para Render
            'http_errors' => false,
            'debug' => true,   // Habilita debug para registrar la comunicación
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
                'Cache-Control' => 'no-cache'
            ]
        ]);
    }

    public function ejecutarPago()
    {
        try {
            // 1. Verificación básica de parámetros
            $paymentId = $this->request->getGet('paymentId');
            $payerId = $this->request->getGet('PayerID');
            
            if (empty($paymentId) || empty($payerId)) {
                throw new Exception('Parámetros paymentId o PayerID faltantes');
            }

            // 2. Obtener token con reintentos
            $accessToken = $this->getAccessTokenWithRetry();
            if (!$accessToken) {
                throw new Exception('No se pudo obtener token de acceso de PayPal');
            }

            // 3. Preparar solicitud
            $client = $this->getHttpClient();
            $response = $client->post("/v1/payments/payment/{$paymentId}/execute", [
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => uniqid()
                ],
                'body' => json_encode(['payer_id' => $payerId])
            ]);

            // 4. Procesar respuesta
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $data = json_decode($body, true);

            log_message('info', "Respuesta PayPal - Status: {$statusCode}, Body: {$body}");

            if ($statusCode !== 200) {
                throw new Exception("Error en API PayPal. Código: {$statusCode}");
            }

            if (!isset($data['state']) || $data['state'] !== 'approved') {
                throw new Exception('El pago no fue aprobado por PayPal');
            }

            // 5. Registrar compra
            $this->registrarCompra($data, $paymentId, $payerId);

            // 6. Redirigir a éxito
            return $this->mostrarVistaCompletada($data);

        } catch (Exception $e) {
            log_message('error', "Error en ejecutarPago: {$e->getMessage()}");
            return $this->mostrarVistaError($e->getMessage());
        }
    }

    protected function getAccessTokenWithRetry($maxRetries = 3)
    {
        $retry = 0;
        while ($retry < $maxRetries) {
            try {
                $client = $this->getHttpClient();
                $response = $client->post('/v1/oauth2/token', [
                    'auth' => [$this->clientId, $this->clientSecret],
                    'form_params' => ['grant_type' => 'client_credentials']
                ]);

                $data = json_decode($response->getBody(), true);
                return $data['access_token'] ?? null;

            } catch (Exception $e) {
                log_message('warning', "Intento {$retry} - Error token: {$e->getMessage()}");
                $retry++;
                sleep(1); // Espera 1 segundo entre reintentos
            }
        }
        return null;
    }

    protected function registrarCompra($data, $paymentId, $payerId)
    {
        try {
            $compraData = [
                'user_id' => session()->get('user_id') ?? 0,
                'monto' => $data['transactions'][0]['amount']['total'] ?? 0,
                'metodo_pago' => 'paypal',
                'status' => 'completado',
                'paypal_order_id' => $paymentId,
                'paypal_payer_id' => $payerId,
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'paypal_response' => json_encode($data)
            ];

            if (!$this->compraModel->registrarCompra($compraData)) {
                log_message('error', 'Error al guardar en BD: '.print_r($compraData, true));
            }
        } catch (Exception $e) {
            log_message('error', "Error al registrar compra: {$e->getMessage()}");
        }
    }

    protected function mostrarVistaCompletada($data)
    {
        $viewData = [
            'success' => 'Pago completado exitosamente',
            'transaction_id' => $data['id'] ?? '',
            'amount' => $data['transactions'][0]['amount']['total'] ?? '0.00',
            'currency' => $data['transactions'][0]['amount']['currency'] ?? 'USD'
        ];

        return view('completada', $viewData);
    }

    protected function mostrarVistaError($message)
    {
        return view('error_compra', [
            'error' => $message ?? 'Ocurrió un error desconocido'
        ]);
    }
}