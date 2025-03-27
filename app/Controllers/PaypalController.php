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
        
        // Configuración desde variables de entorno (recomendado para Render)
        $this->clientId = getenv('PAYPAL_CLIENT_ID') ?: 'AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ';
        $this->clientSecret = getenv('PAYPAL_CLIENT_SECRET') ?: 'ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A';
    }

    /**
     * Obtiene el cliente HTTP configurado para Render
     */
    protected function getHttpClient()
    {
        return Services::curlrequest([
            'base_uri' => 'https://api.sandbox.paypal.com',
            'timeout'  => 30,
            'verify' => false, // Necesario en algunos entornos como Render
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US'
            ]
        ]);
    }

    /**
     * Obtiene el token de acceso de PayPal
     */
    private function getAccessToken()
    {
        $client = $this->getHttpClient();
        
        try {
            $response = $client->post('/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => ['grant_type' => 'client_credentials']
            ]);

            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200 || !isset($data['access_token'])) {
                log_message('error', 'Error PayPal Token: ' . $response->getBody());
                return [
                    'status' => 'error',
                    'message' => 'Error al obtener token de acceso',
                    'debug_info' => $data ?? 'No response data'
                ];
            }

            return $data['access_token'];
        } catch (Exception $e) {
            log_message('error', 'PayPal Token Exception: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Excepción al obtener token de acceso',
                'debug_info' => $e->getMessage()
            ];
        }
    }

    /**
     * Crea un nuevo pago en PayPal
     */
    public function crearPagoPayPal()
    {
        // Verificar autenticación
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Usuario no autenticado'
            ]);
        }

        // Validar datos
        $monto = $this->request->getPost('monto');
        if (!is_numeric($monto) || $monto <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Monto no válido'
            ]);
        }

        // Obtener token
        $accessToken = $this->getAccessToken();
        if (!is_string($accessToken)) {
            return $this->response->setJSON($accessToken);
        }

        $client = $this->getHttpClient();
        
        try {
            $response = $client->post('/v1/payments/payment', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'intent' => 'sale',
                    'payer' => ['payment_method' => 'paypal'],
                    'transactions' => [[
                        'amount' => [
                            'total' => number_format($monto, 2, '.', ''),
                            'currency' => 'USD'
                        ],
                        'description' => 'Pago de pedido',
                        'invoice_number' => uniqid()
                    ]],
                    'redirect_urls' => [
                        'return_url' => site_url('PaypalController/ejecutarPago'),
                        'cancel_url' => site_url('paypal/cancelarPago')
                    ]
                ])
            ]);

            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 201) {
                log_message('error', 'PayPal Create Error: ' . $response->getBody());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al crear pago',
                    'debug_info' => $data
                ]);
            }

            // Buscar URL de aprobación
            foreach ($data['links'] ?? [] as $link) {
                if ($link['rel'] === 'approval_url') {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'redirect_url' => $link['href'],
                        'payment_id' => $data['id']
                    ]);
                }
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se encontró URL de aprobación',
                'debug_info' => $data
            ]);

        } catch (Exception $e) {
            log_message('error', 'PayPal Create Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al procesar pago',
                'debug_info' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ejecuta un pago de PayPal
     */
    public function ejecutarPago()
    {
        $paymentId = $this->request->getGet('paymentId');
        $payerId = $this->request->getGet('PayerID');

        if (empty($paymentId) || empty($payerId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Faltan parámetros necesarios'
            ]);
        }

        $accessToken = $this->getAccessToken();
        if (!is_string($accessToken)) {
            return $this->response->setJSON($accessToken);
        }

        $client = $this->getHttpClient();
        
        try {
            $response = $client->post("/v1/payments/payment/{$paymentId}/execute", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(['payer_id' => $payerId])
            ]);

            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200) {
                log_message('error', 'PayPal Execute Error: ' . $response->getBody());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al ejecutar pago',
                    'debug_info' => $data
                ]);
            }

            if ($data['state'] !== 'approved') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pago no aprobado',
                    'debug_info' => $data
                ]);
            }

            // Registrar compra
            $compraId = $this->compraModel->registrarCompra([
                'user_id' => session()->get('user_id'),
                'monto' => $data['transactions'][0]['amount']['total'],
                'metodo_pago' => 'paypal',
                'status' => 'pagado',
                'paypal_order_id' => $paymentId,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pago completado',
                'compra_id' => $compraId
            ]);

        } catch (Exception $e) {
            log_message('error', 'PayPal Execute Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al procesar pago',
                'debug_info' => $e->getMessage()
            ]);
        }
    }

    public function cancelarPago()
    {
        return $this->response->setJSON([
            'status' => 'canceled',
            'message' => 'Pago cancelado'
        ]);
    }
}