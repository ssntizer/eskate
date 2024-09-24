<?php 
namespace App\Models;

use CodeIgniter\Model;

class SkatesModel extends Model
{
    protected $table = 'skate';
    protected $primaryKey = 'codigo';
    protected $allowedFields = ['codigo', 'velocidad', 'bateria', 'temperatura', 'ID_ubicacion', 'ID_usuario'];

    // Obtener un skate por código
    public function getSkateByCode($codigo)
    {
        try {
            return $this->where('codigo', $codigo)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener skate por código: ' . $e->getMessage());
            return null;
        }
    }

    // Obtener un skate junto con su ubicación
    public function getSkateWithLocation($codigo)
    {
        try {
            // Realizar una consulta que incluya la ubicación relacionada con el skate
            $this->select('skate.*, ubicacion.longitud, ubicacion.latitud, ubicacion.hora');
            $this->join('ubicacion', 'skate.ID_ubicacion = ubicacion.ID_ubicacion');
            return $this->where('skate.codigo', $codigo)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener skate con ubicación: ' . $e->getMessage());
            return null;
        }
    }

    // Actualizar el ID_usuario para un skate existente
    public function addSkate($codigo, $ID_usuario)
    {
        try {
            // Verificar si el código del skate existe
            $existingSkate = $this->getSkateByCode($codigo);
            if (!$existingSkate) {
                return false; // Si no existe, retorna falso
            }

            // Verificar si el ID_usuario es null
            if ($existingSkate['ID_usuario'] !== null) {
                return false; // No se puede vincular si ya tiene un usuario
            }

            // Actualizar el skate con el nuevo ID_usuario
            return $this->update($codigo, ['ID_usuario' => $ID_usuario]);
        } catch (\Exception $e) {
            log_message('error', 'Error al agregar skate: ' . $e->getMessage());
            return false;
        }
    }

    // Desvincular el skate del usuario
    public function unlinkSkate($codigo)
    {
        try {
            // Establecer el ID_usuario como null
            return $this->update($codigo, ['ID_usuario' => null]);
        } catch (\Exception $e) {
            log_message('error', 'Error al desvincular skate: ' . $e->getMessage());
            return false;
        }
    }
}
