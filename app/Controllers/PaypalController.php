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

class PaypalController extends Controller
{
    protected $compraModel;
    protected $apiContext;

    public function __construct()
    {
        $this->compraModel = new CompraModel();
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                'AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ',  // Reemplaza con tus credenciales de PayPal Sandbox
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

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($monto);

        $transaction = new Transaction();
        $transaction->setAmount($amount)->setDescription("Pago de pedido");

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(site_url('PaypalController/ejecutarPago'))
                     ->setCancelUrl(site_url('cancelarPago'));

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
            return json_encode(['status' => 'success', 'redirect_url' => $payment->getApprovalLink()]);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function ejecutarPago()
    {
        $paymentId = $this->request->getGet('paymentId');
        $payerId = $this->request->getGet('PayerID');

        if (!$paymentId || !$payerId) {
            return json_encode(['status' => 'error', 'message' => 'Pago no autorizado']);
        }

        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->apiContext);
            if ($result->getState() === 'approved') {
                // Aquí guardamos la compra en la base de datos
                $data = [
                    'user_id' => session()->get('user_id'),
                    'email' => $this->request->getGet('email'),
                    'address_id' => $this->request->getGet('direccion_id'),
                    'monto' => $result->getTransactions()[0]->getAmount()->getTotal(),
                    'metodo_pago' => 'paypal',
                    'status' => 'pagado',
                    'paypal_order_id' => $paymentId
                ];
                $this->compraModel->registrarCompra($data);
                return json_encode(['status' => 'success', 'message' => 'Pago aprobado']);
            }
            return json_encode(['status' => 'error', 'message' => 'Pago no aprobado']);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
