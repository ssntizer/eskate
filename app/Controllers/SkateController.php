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
    public function detail($id) {
        // Definir los modelos de skates en un array
        $modelos = [
            1 => [
                'id' => 1,
                'nombre' => 'E-Skate 1',
                'precio' => '$299',
                'descripcion' => 'Descripción del Modelo E-Skate 1.',
                'imagen' => 'https://imgs.search.brave.com/tps24H47-2oaLseYhRphCnOSszeFXtoK-3EaI9JezrA/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9za2F0/ZXNlbGVjdHJpY29z/LmNvbS93cC1jb250/ZW50L3VwbG9hZHMv/MjAyMS8wNi9tZWVw/by1taW5pMi1zY2Fs/ZWQuanBlZw'
            ],
            2 => [
                'id' => 2,
                'nombre' => 'E-Skate 2',
                'precio' => '$599',
                'descripcion' => 'Descripción del Modelo E-Skate 2.',
                'imagen' => 'https://imgs.search.brave.com/qH8RsQ019QLQkGLFWZExzsnL4kvsrQ_GwfP-ckTx5pI/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTF1a3dQK3F5b1Mu/anBn'
            ],
            3 => [
                'id' => 3,
                'nombre' => 'E-Skate 3',
                'precio' => '$699',
                'descripcion' => 'Descripción del Modelo E-Skate 3.',
                'imagen' => 'https://imgs.search.brave.com/4hfX1Aw6h9uwaa7HX6i2vtgTdUT3mvVz1GoT5ojtQQE/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NDFNMnd5YTMzMEwu/anBn'
            ],
        ];
    
        // Registro de depuración en el log
        log_message('debug', 'ID recibido: ' . $id);
        log_message('debug', 'Modelos disponibles: ' . print_r(array_keys($modelos), true));
    
        // Verifica si el modelo existe
        if (!array_key_exists($id, $modelos)) {
            log_message('error', 'Modelo no encontrado para el ID: ' . $id);
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Modelo no encontrado");
        }
    
        // Obtener los otros modelos
        $otrosModelos = array_filter($modelos, function($modelo) use ($id) {
            return $modelo['id'] != $id; // Excluye el modelo actual
        });
    
        // Pasa la información a la vista
        log_message('debug', 'Modelo encontrado: ' . print_r($modelos[$id], true));
        return view('skate_detail', [
            'modelo' => $modelos[$id],
            'otrosModelos' => $otrosModelos // Pasa los otros modelos a la vista
        ]);
    }
}