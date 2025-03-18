<?php
namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    protected $allowedFields = [
        'usuario_id', 'monto', 'estado', 'fecha', 'direccion_id', 'email'
    ];

    // Función para guardar un pago
    public function guardarPago($data)
    {
        return $this->insert($data);  // Guarda los datos del pago
    }
}

