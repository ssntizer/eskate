<?php 
namespace App\Controllers;

use App\Models\SkateModel;
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
        $velocidad = $json->velocidad;
        $bateria = $json->bateria;
        $temperatura = $json->temperatura;
        $ID_ubicacion = $json->ID_ubicacion;

        log_message('debug', 'Datos recibidos: codigo=' . $codigo . ', velocidad=' . $velocidad . ', bateria=' . $bateria . ', temperatura=' . $temperatura . ', ID_ubicacion=' . $ID_ubicacion);

        // Verificar si se recibieron todos los datos necesarios
        if (!$codigo || !$velocidad || !$bateria || !$temperatura || !$ID_ubicacion) {
            return $this->fail('Faltan datos necesarios.');
        }

        // Verificar si el código de skate existe
        $skate = $this->skateModel->getSkateByCode($codigo);

        if (!$skate) {
            return $this->failNotFound('Skate no encontrado.');
        }

        // Actualizar el registro del skate
        $updateData = [
            'velocidad' => $velocidad,
            'bateria' => $bateria,
            'temperatura' => $temperatura,
            'ID_ubicacion' => $ID_ubicacion,
        ];

        // Usar where para asegurarse de que se actualice usando el campo "codigo"
        if ($this->skateModel->where('codigo', $codigo)->set($updateData)->update()) {
            return $this->respond(['message' => 'Datos actualizados correctamente.'], 200);
        } else {
            return $this->fail('No se pudieron actualizar los datos.');
        }
    }
}