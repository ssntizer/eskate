<?php 
namespace App\Models;

use CodeIgniter\Model;

class SkateModel extends Model
{
    protected $table = 'skate';
    protected $primaryKey = 'codigo';
    protected $allowedFields = ['codigo', 'velocidad', 'bateria', 'temperatura', 'longitud', 'latitud', 'hora', 'ID_usuario','precio','descripcion','imagen','nombre'];

    // Obtener un skate por código
    public function getSkateByCode($codigo)
    {
        return $this->where('codigo', $codigo)->first();
    }

    // Obtener un skate junto con su ubicación
    public function getSkateWithLocation($codigo)
    {
        // Ahora no es necesario hacer un join, ya que los datos de ubicación están en la tabla skate
        return $this->where('codigo', $codigo)->first();
    }

    // Actualizar el ID_usuario para un skate existente
    public function addSkate($codigo, $ID_usuario)
    {
        // Verificar si el código del skate existe
        $existingSkate = $this->getSkateByCode($codigo);
        if (!$existingSkate) {
            return false; // Si no existe, retorna falso
        }

        // Verificar si el ID_usuario es null
        if ($existingSkate['ID_usuario'] !== null) {
            return false; // No se puede vincular si ya tiene un usuario
        }

        // Actualizar el campo ID_usuario
        $data = ['ID_usuario' => $ID_usuario];
        return $this->update($codigo, $data); // Actualizar el registro
    }

    // Desvincular un skate del usuario
    public function unlinkSkate($codigo)
    {
        // Verificar si el código del skate existe
        $existingSkate = $this->getSkateByCode($codigo);
        if (!$existingSkate) {
            return false; // Si no existe, retorna falso
        }

        // Actualizar el campo ID_usuario a null
        $data = ['ID_usuario' => null];
        return $this->update($codigo, $data); // Actualizar el registro
    }

}