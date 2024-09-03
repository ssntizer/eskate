<?php 
namespace App\Models;

use CodeIgniter\Model;

class SkateModel extends Model
{
    protected $table = 'skate';
    protected $primaryKey = 'codigo'; // Cambia esto si tu clave primaria es diferente

    public function getLatestData()
    {
        return $this->orderBy('ID_usuario', 'DESC')->first(); // Obtener la última entrada de skate
    }

    public function getSkateWithLocation()
    {
        // Obtener la última entrada de skate junto con su ubicación
        return $this->select('skate.*, ubicacion.latitud, ubicacion.longitud, ubicacion.hora')
                    ->join('ubicacion', 'skate.ID_ubicacion = ubicacion.ID_ubicacion')
                    ->orderBy('skate.ID_usuario', 'DESC')
                    ->first(); // Cambia 'ID_usuario' si necesitas un orden diferente
    }
}