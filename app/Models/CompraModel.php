<?php namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table = 'compras';  // Nombre de la tabla en tu base de datos
    protected $primaryKey = 'id_compra';
    protected $allowedFields = ['user_id', 'email', 'address_id', 'monto', 'metodo_pago', 'status', 'paypal_order_id'];

    // Este método guarda la información de la compra en la base de datos
    public function registrarCompra($data)
    {
        // Asegúrate de que el formato de los datos sea correcto
        $this->insert($data);
        return $this->insertID(); // Devuelve el ID de la compra insertada
    }

    // Este método puede ser utilizado para actualizar el estado de la compra después del pago
    public function actualizarEstadoCompra($orderId, $status)
    {
        return $this->set('status', $status)
                    ->where('paypal_order_id', $orderId)
                    ->update();
    }

    // Método para obtener la compra por ID de PayPal
    public function obtenerCompraPorPaypalId($paypalOrderId)
    {
        return $this->where('paypal_order_id', $paypalOrderId)->first();
    }
}
