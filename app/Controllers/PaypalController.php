<?php
namespace App\Controllers;

use App\Models\CompraModel;
use CodeIgniter\Controller;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use Exception;

// Asegúrate de incluir el autoloader de Composer
require_once FCPATH . '../vendor/autoload.php';

class PaypalController extends Controller
{
    protected $compraModel;
    protected $apiContext;

    public function __construct()
    {
        $this->compraModel = new CompraModel();
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                'AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ', // Reemplaza con tus credenciales de PayPal Sandbox
                'ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A'
            )
        );
        $this->apiContext->setConfig([
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'DEBUG',
            'cache.enabled' => true,
        ]);
    }

    public function crearPagoPayPal()
    {
        $usuario_id = session()->get('user_id');
        if (!$usuario_id) {
            return json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        }

        $monto = $this->request->getPost('monto');
        $direccion_id = $this->request->getPost('direccion');
        $email = $this->request->getPost('email');

        // Crear objeto Payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Crear objeto Amount
        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($monto);

        // Crear objeto Transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setDescription("Pago de pedido");

        // Crear las URLs de retorno y cancelación
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(site_url('PaypalController/ejecutarPago'))
                     ->setCancelUrl(site_url('paypal/cancelarPago'));

        // Crear el objeto Payment
        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

        try {
            // Crear el pago
            $payment->create($this->apiContext);

            // Devolver la URL de aprobación de PayPal
            return json_encode([
                'status' => 'success', 
                'redirect_url' => $payment->getApprovalLink(),
                'debug_info' => [
                    'payment_id' => $payment->getId(),
                    'approval_link' => $payment->getApprovalLink(),
                    'payer' => $payment->getPayer(),
                ]
            ]);
        } catch (Exception $e) {
            // Mostrar la excepción directamente en la respuesta
            return json_encode([
                'status' => 'error', 
                'message' => 'Error al crear el pago. Por favor, inténtelo de nuevo más tarde.',
                'debug_info' => [
                    'error_message' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString()
                ]
            ]);
        }
    }

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
                'debug_info' => [
                    'payment_id' => $paymentId,
                    'payer_id' => $payerId,
                ]
            ]);
        }

        try {
            // Obtener el pago desde PayPal
            $payment = Payment::get($paymentId, $this->apiContext);

            // Crear objeto PaymentExecution
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            // Ejecutar el pago
            $result = $payment->execute($execution, $this->apiContext);

            // Verificar si el pago fue aprobado
            if ($result->getState() === 'approved') {
                // Procesa la compra
                $data = [
                    'user_id' => session()->get('user_id'),
                    'email' => $email,
                    'address_id' => $addressId,
                    'monto' => $result->getTransactions()[0]->getAmount()->getTotal(),
                    'metodo_pago' => 'paypal',
                    'status' => 'pagado',
                    'paypal_order_id' => $paymentId
                ];
                $this->compraModel->registrarCompra($data);
                return json_encode([
                    'status' => 'success', 
                    'message' => 'Pago aprobado',
                    'debug_info' => [
                        'payment_state' => $result->getState(),
                        'transaction_amount' => $result->getTransactions()[0]->getAmount()->getTotal(),
                    ]
                ]);
            }

            return json_encode([
                'status' => 'error', 
                'message' => 'Pago no aprobado',
                'debug_info' => [
                    'payment_state' => $result->getState(),
                    'payment_id' => $paymentId
                ]
            ]);
        } catch (Exception $e) {
            // Mostrar la excepción directamente en la respuesta
            return json_encode([
                'status' => 'error', 
                'message' => 'Ocurrió un error al procesar el pago.',
                'debug_info' => [
                    'error_message' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString()
                ]
            ]);
        }
    }
}