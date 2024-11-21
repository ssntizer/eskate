<?php 
namespace App\Controllers;

use App\Models\skatemodel;
use CodeIgniter\RESTful\ResourceController;

class SkateController extends ResourceController
{
    protected $skateModel;

    public function __construct()
    {
        $this->skateModel = new SkateModel();
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
        $codigo = $json->codigo;
        $velocidad = $json->velocidad ?? null; // Permitir que sea nulo
        $bateria = $json->bateria ?? null;     // Permitir que sea nulo
        $temperatura = $json->temperatura ?? null; // Permitir que sea nulo
        $longitud = $json->longitud;           // Nuevo
        $latitud = $json->latitud;             // Nuevo
        $hora = $json->hora;                   // Nuevo
    
        log_message('debug', 'Datos recibidos: codigo=' . $codigo . ', velocidad=' . $velocidad . ', bateria=' . $bateria . ', temperatura=' . $temperatura . ', longitud=' . $longitud . ', latitud=' . $latitud . ', hora=' . $hora);
    
        // Verificar si se recibió el código
        if (!$codigo) {
            return $this->fail('Faltan datos necesarios: el código es obligatorio.');
        }
    
        // Verificar si el código de skate existe
        $skate = $this->skateModel->getSkateByCode($codigo);
    
        if (!$skate) {
            return $this->failNotFound('Skate no encontrado.');
        }
    
        // Preparar datos para la actualización
        $updateData = [
            'velocidad' => $velocidad,
            'bateria' => $bateria,
            'temperatura' => $temperatura,
            'longitud' => $longitud,
            'latitud' => $latitud,
            'hora' => $hora,  // Usar la hora recibida
        ];
    
        // Actualizar la tabla skate usando el campo "codigo"
        if ($this->skateModel->where('codigo', $codigo)->set($updateData)->update()) {
            return $this->respond(['message' => 'Datos actualizados correctamente.'], 200);
        } else {
            return $this->fail('No se pudieron actualizar los datos del skate.');
        }
    }

 
   
}
