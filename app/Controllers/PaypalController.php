<?php
namespace App\Controllers;

use App\Models\CompraModel;
use CodeIgniter\Controller;
use GuzzleHttp\Client;
use Exception;

class PaypalController extends Controller
{
    protected $compraModel;
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;
    protected $client;

    public function __construct()
    {
        $this->compraModel = new CompraModel();
        $this->clientId = 'AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ'; // Reemplaza con tu Client ID
        $this->clientSecret = 'ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A'; // Reemplaza con tu Client Secret
        $this->client = new Client();
        $this->accessToken = $this->getAccessToken(); // Obtener el token de acceso
    }

    // Obtener el token de acceso de PayPal
    private function getAccessToken()
    {
        try {
            $response = $this->client->post('https://api.sandbox.paypal.com/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Error al obtener token de acceso.', 'debug_info' => $e->getMessage()]);
        }
    }

    // Crear un pago de PayPal
    public function crearPagoPayPal()
    {
        $usuario_id = session()->get('user_id');
        if (!$usuario_id) {
            return json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        }

        $monto = $this->request->getPost('monto');
        $direccion_id = $this->request->getPost('direccion');
        $email = $this->request->getPost('email');

        try {
            // Solicitar el pago a la API de PayPal
            $response = $this->client->post('https://api.sandbox.paypal.com/v1/payments/payment', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'intent' => 'sale',
                    'payer' => [
                        'payment_method' => 'paypal'
                    ],
                    'transactions' => [
                        [
                            'amount' => [
                                'total' => $monto,
                                'currency' => 'USD'
                            ],
                            'description' => 'Pago de pedido',
                        ]
                    ],
                    'redirect_urls' => [
                        'return_url' => site_url('PaypalController/ejecutarPago'),
                        'cancel_url' => site_url('paypal/cancelarPago')
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Buscar la URL de aprobación y devolverla
            $approvalLink = null;
            foreach ($data['links'] as $link) {
                if ($link['rel'] == 'approval_url') {
                    $approvalLink = $link['href'];
                    break;
                }
            }

            return json_encode([
                'status' => 'success',
                'redirect_url' => $approvalLink,
                'debug_info' => [
                    'payment_id' => $data['id'],
                    'approval_link' => $approvalLink,
                ]
            ]);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Error al crear el pago. Por favor, inténtelo de nuevo más tarde.',
                'debug_info' => $e->getMessage()
            ]);
        }
    }

    // Ejecutar un pago de PayPal
    public function ejecutarPago()
    {
        $paymentId = $this->request->getGet('paymentId');
        $payerId = $this->request->getGet('PayerID');
        $email = $this->request->getGet('email');
        $addressId = $this->request->getGet('address_id');

        if (!$paymentId || !$payerId) {
            return json_encode([
                'status' => 'error',
                'message' => 'Pago no autorizado',
            ]);
        }

        try {
            // Ejecutar el pago
            $response = $this->client->post("https://api.sandbox.paypal.com/v1/payments/payment/{$paymentId}/execute", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'payer_id' => $payerId
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Verificar si el pago fue aprobado
            if ($data['state'] === 'approved') {
                // Registrar la compra en la base de datos
                $this->compraModel->registrarCompra([
                    'user_id' => session()->get('user_id'),
                    'email' => $email,
                    'address_id' => $addressId,
                    'monto' => $data['transactions'][0]['amount']['total'],
                    'metodo_pago' => 'paypal',
                    'status' => 'pagado',
                    'paypal_order_id' => $paymentId
                ]);

                return json_encode([
                    'status' => 'success',
                    'message' => 'Pago aprobado',
                    'debug_info' => $data
                ]);
            }

            return json_encode([
                'status' => 'error',
                'message' => 'Pago no aprobado',
            ]);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar el pago.',
                'debug_info' => $e->getMessage()
            ]);
        }
    }
}