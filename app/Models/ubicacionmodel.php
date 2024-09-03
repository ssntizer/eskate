<?php 
namespace App\Models;

use CodeIgniter\Model;

class UbicacionModel extends Model
{
    protected $table = 'ubicacion';
    protected $primaryKey = 'ID_ubicacion';
    protected $allowedFields = ['ID_ubicacion', 'longitud', 'latitud', 'hora'];

    public function getLocationBySkateCode($codigo)
    {
        // Asumimos que `ID_ubicacion` en la tabla `skate` se usa para identificar la ubicación
        $skateModel = new SkateModel();
        $skate = $skateModel->getSkateByCode($codigo);
        
        if ($skate) {
            return $this->where('ID_ubicacion', $skate['ID_ubicacion'])->first();
        }
        
        return null;
    }
}
