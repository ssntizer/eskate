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

    // Función para obtener datos del skate
    public function getSkateData($codigo)
    {
        // Obtener los datos del skate por el código
        $skate = $this->skateModel->getSkateByCode($codigo);
        
        if (!$skate) {
            return $this->failNotFound('Skate no encontrado.');
        }

        // Devolver los datos del skate
        return $this->respond($skate, 200);
    }
    // Función para activar o desactivar el buzzer
    public function activateBuzzer($codigo)
    {
        // Verificar si el skate existe
        $skate = $this->skateModel->getSkateByCode($codigo);

        if (!$skate) {
            return $this->failNotFound('Skate no encontrado.');
        }

        // Cambiar el estado del buzzer en la base de datos (toggle)
        $newBuzzerState = !$skate['buzzer']; // Si el buzzer está en 1, lo cambia a 0 y viceversa
        $this->skateModel->where('codigo', $codigo)->set(['buzzer' => $newBuzzerState])->update();

        return $this->respond(['message' => 'Buzzer ' . ($newBuzzerState ? 'activado' : 'desactivado') . '.'], 200);
    }

    // Nueva función para que la ESP32 consulte el estado del buzzer
    public function getBuzzerState($codigo)
    {
        // Verificar si el skate existe
        $skate = $this->skateModel->getSkateByCode($codigo);

        if (!$skate) {
            return $this->failNotFound('Skate no encontrado.');
        }

        // Devolver el estado del buzzer
        return $this->respond(['buzzer' => $skate['buzzer']], 200);
    }

public function activateBuzzerTimed($codigo)
{
    // Verificar si el skate existe
    $skate = $this->skateModel->getSkateByCode($codigo);

    if (!$skate) {
        return $this->failNotFound('Skate no encontrado.');
    }

    // Enviar una solicitud a la ESP32 para activar el buzzer por 10 segundos
    $buzzerURL = "http://192.168.118.98:80/activate-buzzer?duration=10";  // Reemplaza con la IP de tu ESP32

    $client = \Config\Services::curlrequest();
    $response = $client->get($buzzerURL);

    if ($response->getStatusCode() == 200) {
        return $this->respond(['message' => 'Buzzer activado durante 10 segundos.'], 200);
    } else {
        return $this->fail('Error al activar el buzzer.');
    }
}
}