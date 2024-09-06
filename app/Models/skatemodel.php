<?php 
namespace App\Models;

use CodeIgniter\Model;

class SkateModel extends Model
{
    protected $table = 'skate';
    protected $primaryKey = 'codigo';
    protected $allowedFields = ['codigo', 'velocidad', 'bateria', 'temperatura', 'ID_ubicacion', 'ID_usuario'];

    public function getSkateByCode($codigo)
    {
        return $this->where('codigo', $codigo)->first();
    }

    public function getSkateWithLocation($codigo)
    {
        // Aquí debes realizar una consulta que incluya la ubicación relacionada con el skate
        $this->select('skate.*, ubicacion.longitud, ubicacion.latitud, ubicacion.hora');
        $this->join('ubicacion', 'skate.ID_ubicacion = ubicacion.ID_ubicacion');
        return $this->where('skate.codigo', $codigo)->first();
    }
}
