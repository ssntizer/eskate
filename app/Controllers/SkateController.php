<?php
// Este script asume que tienes instaladas las librerías de CodeIgniter y php-mqtt/client
// via Composer. Ejecución: php mqtt_subscriber.php

require 'vendor/autoload.php';

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;
// Asumiendo que esta clase existe en el entorno CodeIgniter
use CodeIgniter\Database\ConnectionInterface; 

/**
 * Clase para suscribirse a un tópico MQTT y procesar los datos del skate.
 */
class MqttSubscriber {
    protected $db;
    protected $skateTable = 'skate';
    protected $skateTrackingTable = 'skate_tracking';

    // ⚠️ BROKER Y PUERTO SINCRONIZADOS CON LA PLACA
    const BROKER_SERVER = 'test.mosquitto.org';
    const BROKER_PORT = 1883; 
    const TOPIC = 'skate/data';

    public function __construct(ConnectionInterface &$db) {
        $this->db = $db;
    }

    public function run() {
        $server = self::BROKER_SERVER;
        $port = self::BROKER_PORT;
        // Generar un Client ID único para prevenir colisiones en el broker
        $clientId = 'php_mqtt_subscriber_' . uniqid(); 
        $topic = self::TOPIC;

        $connectionSettings = (new ConnectionSettings)
            ->setKeepAliveInterval(60) 
            ->setConnectTimeout(10);

        $mqtt = new MqttClient($server, $port, $clientId);
        
        try {
            // Conexión TCP simple (no SSL/TLS)
            $mqtt->connect($connectionSettings, false); 
            echo "Connected to MQTT broker: {$server}:{$port} as {$clientId}\n";

            // Suscribirse al tópico
            $mqtt->subscribe($topic, function ($topic, $message, $retained, $matchedWildcards) {
                echo "\n------------------------------------------------\n";
                echo "Received message on topic [$topic]: $message\n";
                $this->processMessage($message);
                echo "------------------------------------------------\n";
            }, 0);

            // Mantener la conexión activa y escuchar mensajes
            $mqtt->loop(true);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            // Esperar y reintentar la conexión
            sleep(5); 
            $this->run(); 
        }
    }

    protected function processMessage($message) {
        try {
            $data = json_decode($message, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Invalid JSON: " . json_last_error_msg() . "\n";
                return;
            }

            // Extracción de datos
            $codigo = $data['codigo'] ?? null;
            $velocidad = $data['velocidad'] ?? null;
            $bateria = $data['bateria'] ?? null;
            $temperatura = $data['temperatura'] ?? null;
            $longitud = $data['longitud'] ?? null;
            $latitud = $data['latitud'] ?? null;
            $hora = $data['hora'] ?? null;
            
            error_log("Datos recibidos: codigo=$codigo, velocidad=$velocidad, longitud=$longitud");

            if (!$codigo || !$longitud || !$latitud) {
                echo "Faltan datos necesarios: código, longitud y latitud son obligatorios.\n";
                return;
            }

            // 1. Verify if skate exists
            $query = $this->db->table($this->skateTable)->where('codigo', $codigo)->get();
            if ($query->getNumRows() === 0) {
                echo "Skate no encontrado (codigo: $codigo).\n";
                return;
            }

            // 2. Update skate table (Último estado)
            $updateData = [
                'velocidad' => $velocidad,
                'bateria' => $bateria,
                'temperatura' => $temperatura,
                'longitud' => $longitud,
                'latitud' => $latitud,
                'hora' => $hora
            ];
            $this->db->table($this->skateTable)->where('codigo', $codigo)->update($updateData);

            // 3. Insert into skate_tracking (Historial de posición)
            $this->db->table($this->skateTrackingTable)->insert([
                'codigo' => $codigo,
                'longitud' => $longitud,
                'latitud' => $latitud,
                // Asume que la columna 'timestamp' se llena automáticamente por la DB o se usa $hora
            ]);

            // 4. Delete old tracking records (older than 3 days)
            $threeDaysAgo = date('Y-m-d H:i:s', strtotime('-3 days'));
            $this->db->table($this->skateTrackingTable)
                     ->where('codigo', $codigo)
                     ->where('timestamp <', $threeDaysAgo)
                     ->delete();

            echo "Datos guardados y registros antiguos eliminados correctamente.\n";
        } catch (Exception $e) {
            error_log("Error processing message: " . $e->getMessage());
        }
    }
}

// Initialization and run
try {
    // Intenta inicializar CodeIgniter y la base de datos
    require 'system/autoload.php';
    $db = \Config\Database::connect();
    
    // Run subscriber
    $subscriber = new MqttSubscriber($db);
    $subscriber->run();
    
} catch (Exception $e) {
    echo "\nFATAL ERROR: Failed to initialize CodeIgniter or DB connection.\n";
    echo "Check your environment setup: " . $e->getMessage() . "\n";
}