<?php 
namespace App\Models;

use CodeIgniter\Model;

class UbicacionModel extends Model
{
    protected $table = 'ubicacion'; // Nombre de la tabla
    protected $primaryKey = 'ID_ubicacion'; // Cambia esto si tu clave primaria es diferente

    public function getLatestLocation()
    {
        return $this->orderBy('ID_ubicacion', 'DESC')->first(); // Obtener la última ubicación
    }
}