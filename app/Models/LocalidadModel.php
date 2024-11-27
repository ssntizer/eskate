<?php
namespace App\Models;

use CodeIgniter\Model;

class LocalidadModel extends Model
{
    protected $table = 'localidades';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_provincia', 'localidad'];

    // Esta función nos permite obtener las localidades por provincia
    public function obtenerLocalidadesPorProvincia($provinciaId)
    {
        return $this->where('id_provincia', $provinciaId)->findAll();
    }
}