<?php

namespace App\Models;

use CodeIgniter\Model;

class DireccionModel extends Model
{
    protected $table = 'direccion';
    protected $primaryKey = 'ID_direccion';
    protected $allowedFields = ['calle','ciudad', 'provincia', 'numero', 'ID_usuario'];

    // Devuelve todas las direcciones de un usuario específico
    public function getDireccionesPorUsuario($idUsuario)
    {
        return $this->where('ID_usuario', $idUsuario)->findAll();
    }
    public function guardarDireccion($data)
    {
        return $this->insert($data);
    }

}