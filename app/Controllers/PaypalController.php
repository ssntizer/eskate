<?php
namespace App\Controllers;

use App\Models\CompraModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use Exception;

class PaypalController extends Controller
{
    protected $compraModel;
    protected $clientId;
    protected $clientSecret;
    protected $client;

    public function __construct()
    {
        $this->compraModel = new CompraModel();
        
        // Configuración desde variables de entorno (recomendado)
        $this->clientId = getenv('PAYPAL_CLIENT_ID') ?: 'AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ';
        $this->clientSecret = getenv('PAYPAL_CLIENT_SECRET') ?: 'ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A';
        
        // Configurar el cliente HTTP de CodeIgniter
        $this->client = Services::curlrequest([
            'baseURI' => 'https://api.sandbox.paypal.com',
            'timeout' => 30,
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
        try {
            $response = $this->client->post('/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => ['grant_type' => 'client_credentials']
            ]);

            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200 || !isset($data['access_token'])) {
                log_message('error', 'Error al obtener token PayPal: ' . print_r($data, true));
                return [
                    'status' => 'error',
                    'message' => 'Error al obtener token de acceso',
                    'debug_info' => $data ?? 'No response data'
                ];
            }

            return $data['access_token'];
        } catch (Exception $e) {
            log_message('error', 'Excepción al obtener token PayPal: ' . $e->getMessage());
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
        $usuario_id = session()->get('user_id');
        if (!$usuario_id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Usuario no autenticado'
            ]);
        }

        // Obtener datos del request
        $monto = $this->request->getPost('monto');
        $direccion_id = $this->request->getPost('direccion');
        $email = $this->request->getPost('email');

        // Validar datos
        if (!is_numeric($monto) || $monto <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Monto no válido'
            ]);
        }

        // Obtener token de acceso
        $accessToken = $this->getAccessToken();
        if (!is_string($accessToken)) {
            return $this->response->setJSON($accessToken);
        }

        try {
            // Crear pago en PayPal
            $response = $this->client->post('/v1/payments/payment', [
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
                        'return_url' => site_url('PaypalController/ejecutarPago') . '?email=' . urlencode($email) . '&address_id=' . $direccion_id,
                        'cancel_url' => site_url('paypal/cancelarPago')
                    ]
                ])
            ]);

            $data = json_decode($response->getBody(), true);

            // Verificar respuesta
            if ($response->getStatusCode() !== 201 || !isset($data['id'])) {
                log_message('error', 'Error al crear pago PayPal: ' . print_r($data, true));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al crear el pago en PayPal',
                    'debug_info' => $data
                ]);
            }

            // Buscar URL de aprobación
            $approvalLink = null;
            foreach ($data['links'] as $link) {
                if ($link['rel'] === 'approval_url') {
                    $approvalLink = $link['href'];
                    break;
                }
            }

            if (!$approvalLink) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No se pudo obtener URL de aprobación',
                    'debug_info' => $data
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'redirect_url' => $approvalLink,
                'payment_id' => $data['id']
            ]);

        } catch (Exception $e) {
            log_message('error', 'Excepción al crear pago PayPal: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al procesar la solicitud de pago',
                'debug_info' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ejecuta un pago de PayPal después de la aprobación del usuario
     */
    public function ejecutarPago()
    {
        // Obtener parámetros
        $paymentId = $this->request->getGet('paymentId');
        $payerId = $this->request->getGet('PayerID');
        $email = $this->request->getGet('email');
        $addressId = $this->request->getGet('address_id');

        // Validar parámetros
        if (empty($paymentId) || empty($payerId)) {
            log_message('error', 'Faltan parámetros para ejecutar pago PayPal');
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Faltan parámetros necesarios (paymentId o PayerID)'
            ]);
        }

        // Obtener token de acceso
        $accessToken = $this->getAccessToken();
        if (!is_string($accessToken)) {
            return $this->response->setJSON($accessToken);
        }

        try {
            // Ejecutar el pago en PayPal
            $response = $this->client->post(
                "/v1/payments/payment/{$paymentId}/execute",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode(['payer_id' => $payerId])
                ]
            );

            $data = json_decode($response->getBody(), true);

            // Verificar respuesta
            if ($response->getStatusCode() !== 200) {
                log_message('error', 'Error al ejecutar pago PayPal: ' . print_r($data, true));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al ejecutar el pago en PayPal',
                    'debug_info' => $data
                ]);
            }

            // Verificar estado del pago
            if ($data['state'] !== 'approved') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'El pago no fue aprobado',
                    'debug_info' => $data
                ]);
            }

            // Registrar la compra en la base de datos
            $compraId = $this->compraModel->registrarCompra([
                'user_id' => session()->get('user_id'),
                'email' => $email,
                'address_id' => $addressId,
                'monto' => $data['transactions'][0]['amount']['total'],
                'metodo_pago' => 'paypal',
                'status' => 'pagado',
                'paypal_order_id' => $paymentId,
                'paypal_payer_id' => $payerId,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]);

            if (!$compraId) {
                log_message('error', 'Error al registrar compra en BD');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pago aprobado pero error al registrar la compra',
                    'debug_info' => 'Error en base de datos'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pago completado exitosamente',
                'compra_id' => $compraId,
                'paypal_data' => $data
            ]);

        } catch (Exception $e) {
            log_message('error', 'Excepción al ejecutar pago PayPal: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al procesar el pago',
                'debug_info' => $e->getMessage()
            ]);
        }
    }

    /**
     * Maneja la cancelación de pagos
     */
    public function cancelarPago()
    {
        return $this->response->setJSON([
            'status' => 'canceled',
            'message' => 'El pago fue cancelado por el usuario'
        ]);
    }
}