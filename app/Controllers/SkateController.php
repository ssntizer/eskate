<?php 
namespace App\Controllers;

use App\Models\SkateModel;
use App\Models\SkateTrackingModel;
use CodeIgniter\RESTful\ResourceController;

class SkateController extends ResourceController
{
    protected $skateModel;
    protected $skateTrackingModel;

    public function __construct()
    {
        $this->skateModel = new SkateModel();
        $this->skateTrackingModel = new SkateTrackingModel();
    }

    // Función para recibir datos desde la ESP32 y actualizar los registros
    public function updateSkateData()
    {
        // Intentar obtener los datos como JSON
        $json = $this->request->getJSON();
        
        // Verificar si se recibió el JSON
        if (!$json) {
            return $this->fail('No se recibieron datos en formato JSON.');
        }
    
        // Extraer los datos del JSON
        $codigo = $json->codigo ?? null;
        $velocidad = $json->velocidad ?? null;
        $bateria = $json->bateria ?? null;
        $temperatura = $json->temperatura ?? null;
        $longitud = $json->longitud ?? null;
        $latitud = $json->latitud ?? null;
        $hora = $json->hora ?? null;

        log_message('debug', 'Datos recibidos: codigo=' . $codigo . ', velocidad=' . $velocidad . ', bateria=' . $bateria . ', temperatura=' . $temperatura . ', longitud=' . $longitud . ', latitud=' . $latitud . ', hora=' . $hora);
    
        // Verificar si se recibió el código y la ubicación
        if (!$codigo || !$longitud || !$latitud) {
            return $this->fail('Faltan datos necesarios: código, longitud y latitud son obligatorios.');
        }
    
        // Verificar si el código de skate existe
        $skate = $this->skateModel->where('codigo', $codigo)->first();
    
        if (!$skate) {
            return $this->failNotFound('Skate no encontrado.');
        }
    
        // Actualizar la tabla skate con la última ubicación y datos generales
        $updateData = [
            'velocidad' => $velocidad,
            'bateria' => $bateria,
            'temperatura' => $temperatura,
            'longitud' => $longitud,
            'latitud' => $latitud,
            'hora' => $hora
        ];
    
        $this->skateModel->where('codigo', $codigo)->set($updateData)->update();

        // Insertar la nueva ubicación en skate_tracking
        $this->skateTrackingModel->insert([
            'codigo' => $codigo,
            'longitud' => $longitud,
            'latitud' => $latitud
        ]);

        return $this->respond(['message' => 'Datos guardados correctamente.'], 200);
    }
}
