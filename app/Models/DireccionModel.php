<?php

namespace App\Models;

use CodeIgniter\Model;

class DireccionModel extends Model
{
    protected $table = 'direccion';
    protected $primaryKey = 'ID_direccionPrimaria';
    protected $allowedFields = [
        'calle', 'ID_provincia', 'numero', 'ID_usuario', 'ID_localidad'
    ];

    // Obtener todas las direcciones de un usuario
    public function getDireccionesPorUsuario($userId)
    {
        return $this->where('ID_usuario', $userId)->findAll();
    }

    // Obtener provincia por ID
    public function getProvinciaPorId($provinciaId)
    {
        return $this->join('provincias', 'provincias.ID_provincia = direccion.ID_provincia')
                    ->where('ID_direccionPrimaria', $provinciaId)
                    ->first();
    }

    // Obtener localidad por ID
    public function getLocalidadPorId($localidadId)
    {
        return $this->join('localidades', 'localidades.ID_localidad = direccion.ID_localidad')
                    ->where('ID_direccionPrimaria', $localidadId)
                    ->first();
    }
    public function guardarDireccion($data)
    {
        // Asegúrate de que los datos estén correctos
        $direccionData = [
            'calle'        => $data['calle'],
            'numero'       => $data['numero'],
            'ID_provincia' => $data['provincia'], // Guardar el ID de la provincia
            'ID_localidad' => $data['localidad'], // Guardar el ID de la localidad
            'ID_usuario'   => $data['ID_usuario']
        ];

        // Insertar la nueva dirección en la base de datos
        return $this->insert($direccionData);
    }

}