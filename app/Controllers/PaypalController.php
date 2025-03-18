<?php
namespace App\Controllers;

use App\Models\PagoModel;
use CodeIgniter\Controller;

class PaypalController extends Controller
{
    protected $pagoModel;

    public function __construct()
    {
        $this->pagoModel = new PagoModel();
    }

    // Simula el pago con PayPal
    public function simularPagoPayPal()
    {
        // Obtener el ID del usuario desde la sesión
        $usuario_id = session()->get('user_id');  // Asegúrate de que esté disponible

        if (!$usuario_id) {
            return json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        }

        // Obtener el monto, dirección (ID) y email desde el formulario
        $monto = $this->request->getPost('monto');
        $direccion_id = $this->request->getPost('direccion');  // Ahora recibimos el ID de la dirección
        $email = $this->request->getPost('email');

        // Simulamos una transacción exitosa
        $datosPago = [
            'usuario_id' => $usuario_id,
            'monto' => $monto,
            'direccion_id' => $direccion_id,   // Guardamos el ID de la dirección
            'email' => $email,                  // Guardamos el email
            'estado' => 'Completado',
            'fecha' => date('Y-m-d H:i:s')
        ];

        // Guardar el pago en la base de datos
        $resultado = $this->pagoModel->guardarPago($datosPago);

        if ($resultado) {
            return json_encode(['status' => 'success', 'message' => 'Pago simulado con éxito']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Error al procesar el pago']);
        }
    }
}
