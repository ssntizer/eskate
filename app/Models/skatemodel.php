<?php 
namespace App\Models;

use CodeIgniter\Model;

class SkateModel extends Model
{
    protected $table = 'skate';
    protected $primaryKey = 'codigo';
    protected $allowedFields = ['codigo', 'velocidad', 'bateria', 'temperatura', 'ID_ubicacion', 'ID_usuario'];

    // Obtener un skate por código
    public function getSkateByCode($codigo)
    {
        return $this->where('codigo', $codigo)->first();
    }

    // Obtener un skate junto con su ubicación
    public function getSkateWithLocation($codigo)
    {
        // Realizar una consulta que incluya la ubicación relacionada con el skate
        $this->select('skate.*, ubicacion.longitud, ubicacion.latitud, ubicacion.hora');
        $this->join('ubicacion', 'skate.ID_ubicacion = ubicacion.ID_ubicacion');
        return $this->where('skate.codigo', $codigo)->first();
    }

    // Agregar un nuevo skate a la base de datos
    public function addSkate($codigo, $ID_usuario)
    {
        // Verificar si el código del skate ya existe
        $existingSkate = $this->getSkateByCode($codigo);
        if ($existingSkate) {
            return false; // Si ya existe, retorna falso
        }

        // Insertar un nuevo skate en la base de datos
        $data = [
            'codigo' => $codigo,
            'ID_usuario' => $ID_usuario, // El ID del usuario que lo agregó
            // 'velocidad', 'bateria', 'temperatura', 'ID_ubicacion' serán agregados automáticamente después
        ];

        return $this->insert($data); // Insertar el nuevo registro
    }
}