<?php
require 'vendor/autoload.php';

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;
use CodeIgniter\Database\ConnectionInterface;

class MqttSubscriber {
    protected $db;
    protected $skateTable = 'skate';
    protected $skateTrackingTable = 'skate_tracking';

    public function __construct(ConnectionInterface &$db) {
        $this->db = $db;
    }

    public function run() {
        $server = 'broker.hivemq.com';
        $port = 1883;
        $clientId = 'php_mqtt_subscriber_' . uniqid();
        $topic = 'skate/data';

        $connectionSettings = (new ConnectionSettings)
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(10);

        $mqtt = new MqttClient($server, $port, $clientId);
        try {
            $mqtt->connect($connectionSettings);
            echo "Connected to MQTT broker.\n";

            $mqtt->subscribe($topic, function ($topic, $message, $retained, $matchedWildcards) {
                echo "Received message on topic [$topic]: $message\n";
                $this->processMessage($message);
            }, 0);

            $mqtt->loop(true);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    protected function processMessage($message) {
        try {
            $data = json_decode($message, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Invalid JSON: " . json_last_error_msg() . "\n";
                return;
            }

            $codigo = $data['codigo'] ?? null;
            $velocidad = $data['velocidad'] ?? null;
            $bateria = $data['bateria'] ?? null;
            $temperatura = $data['temperatura'] ?? null;
            $longitud = $data['longitud'] ?? null;
            $latitud = $data['latitud'] ?? null;
            $hora = $data['hora'] ?? null;

            error_log("Datos recibidos: codigo=$codigo, velocidad=$velocidad, bateria=$bateria, temperatura=$temperatura, longitud=$longitud, latitud=$latitud, hora=$hora");

            if (!$codigo || !$longitud || !$latitud) {
                echo "Faltan datos necesarios: código, longitud y latitud son obligatorios.\n";
                return;
            }

            // Verify if skate exists
            $query = $this->db->table($this->skateTable)->where('codigo', $codigo)->get();
            if ($query->getNumRows() === 0) {
                echo "Skate no encontrado.\n";
                return;
            }

            // Update skate table
            $updateData = [
                'velocidad' => $velocidad,
                'bateria' => $bateria,
                'temperatura' => $temperatura,
                'longitud' => $longitud,
                'latitud' => $latitud,
                'hora' => $hora
            ];
            $this->db->table($this->skateTable)->where('codigo', $codigo)->update($updateData);

            // Insert into skate_tracking
            $this->db->table($this->skateTrackingTable)->insert([
                'codigo' => $codigo,
                'longitud' => $longitud,
                'latitud' => $latitud
            ]);

            // Delete old tracking records (older than 3 days)
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

// Initialize CodeIgniter database
require 'system/autoload.php';
$db = \Config\Database::connect();

// Run subscriber
$subscriber = new MqttSubscriber($db);
$subscriber->run();