<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaypalController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pago_model');
    }

    // Carga la vista del pago
    public function index() {
        $this->load->view('paypal_pago');
    }

    // Simula un pago con PayPal
    public function simularPagoPayPal() {
        $usuario_id = $this->input->post('usuario_id'); // ID del usuario
        $monto = $this->input->post('monto'); // Monto del pago

        // Simulamos una transacción exitosa
        $datosPago = [
            'usuario_id' => $usuario_id,
            'monto' => $monto,
            'estado' => 'Completado',
            'fecha' => date('Y-m-d H:i:s')
        ];

        $resultado = $this->Pago_model->guardarPago($datosPago);

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Pago simulado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al procesar el pago']);
        }
    }

    // Obtiene todos los pagos simulados
    public function obtenerPagos() {
        $pagos = $this->Pago_model->obtenerPagos();
        echo json_encode($pagos);
    }
}
?>
