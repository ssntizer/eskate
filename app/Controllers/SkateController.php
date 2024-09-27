<?php 
namespace App\Controllers;

use App\Models\skateModel;
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
        $ID_ubicacion = $json->ID_ubicacion;   // Este campo se mantiene requerido
        $longitud = $json->longitud;            // Nuevo
        $latitud = $json->latitud;              // Nuevo
        $hora = $json->hora;                    // Nuevo
    
        log_message('debug', 'Datos recibidos: codigo=' . $codigo . ', velocidad=' . $velocidad . ', bateria=' . $bateria . ', temperatura=' . $temperatura . ', ID_ubicacion=' . $ID_ubicacion . ', longitud=' . $longitud . ', latitud=' . $latitud . ', hora=' . $hora);
    
        // Verificar si se recibió el código y ID_ubicacion
        if (!$codigo || !$ID_ubicacion) {
            return $this->fail('Faltan datos necesarios: codigo e ID_ubicacion son obligatorios.');
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
            'ID_ubicacion' => $ID_ubicacion,
        ];
    
        // Usar where para asegurarse de que se actualice usando el campo "codigo"
        if ($this->skateModel->where('codigo', $codigo)->set($updateData)->update()) {
            // Cargar el modelo de ubicación
            $ubicacionModel = new \App\Models\UbicacionModel();
            $ubicacionData = [
                'longitud' => $longitud,
                'latitud' => $latitud,
                'hora' => $hora,  // Usar la hora recibida
            ];
    
            // Realizar el update de la ubicación usando el ID_ubicacion
            if ($ubicacionModel->where('ID_ubicacion', $ID_ubicacion)->set($ubicacionData)->update()) {
                return $this->respond(['message' => 'Datos actualizados correctamente.'], 200);
            } else {
                return $this->fail('No se pudo actualizar la ubicación.');
            }
        } else {
            return $this->fail('No se pudieron actualizar los datos del skate.');
        }
    }
}