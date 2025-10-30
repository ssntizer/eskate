<?php
// === LÓGICA PHP ===

// Conectar a la base de datos
// ASUMIMOS que CodeIgniter u otro framework ha cargado la configuración de base de datos
$db = \Config\Database::connect();
$codigo = 'YYYYY1';

// Bandera para detectar si la solicitud es AJAX (para obtener solo el JSON)
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Obtener los datos del recorrido
$query = $db->query("SELECT longitud, latitud FROM skate_tracking WHERE codigo = '$codigo' ORDER BY timestamp ASC");
$waypoints = [];

foreach ($query->getResultArray() as $row) {
    // Leaflet usa [latitud, longitud]
    $waypoints[] = [(float)$row['latitud'], (float)$row['longitud']]; 
}

// === RESPUESTA AJAX: Si la solicitud es AJAX, devuelve solo el JSON y termina ===
if ($is_ajax) {
    header('Content-Type: application/json');
    echo json_encode($waypoints);
    exit; // Crucial para detener la generación del HTML
}

// Convertir los waypoints en formato JSON para usarlos en JavaScript (carga inicial)
$waypointsJson = json_encode($waypoints);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trayectoria del Skate | E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* (Se asume que el CSS es correcto y no requiere cambios para la funcionalidad) */
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 70px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #005f87;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 1.5rem;
            font-family: "Quicksand", sans-serif;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header-buttons {
            display: flex;
            gap: 15px;
        }

        .header-buttons a {
            color: #333;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 20px;
            border-radius: 50px;
            background-color: #ffcc00;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .header-buttons a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700;
            transition: width 0.3s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .header-buttons a:hover {
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 204, 0, 0.4);
        }

        .header-buttons a:hover::before {
            width: 100%;
        }

        .container {
            margin-top: 30px;
            padding-bottom: 60px;
            flex: 1;
        }

        .container h2 {
            text-align: center;
            color: #ffcc00;
            font-family: 'Baskervville', serif;
            margin-bottom: 25px;
            position: relative;
        }

        .container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
        }

        .map-container {
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border: 1px solid #004b6b;
            position: relative;
        }

        .map-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
            z-index: 1000;
        }

        footer {
            background-color: #004b6b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            position: relative;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        .leaflet-container {
            background-color: #005f87 !important;
        }

        .leaflet-popup-content-wrapper, 
        .leaflet-popup-tip {
            background-color: #005f87;
            color: #ffffff;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.4);
        }

        .leaflet-popup-content-wrapper a {
            color: #ffcc00;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                padding: 10px;
            }
            
            .header h1 {
                margin-bottom: 10px;
                text-align: center;
            }
            
            .header-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .map-container {
                height: 400px;
            }
        }

        @media (max-width: 576px) {
            .map-container {
                height: 350px;
            }
            
            .container {
                padding-bottom: 80px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Bienvenido, <?= session()->get('username') ?>!</h1>
    <div class="header-buttons">
        <a href="javascript:history.back()">Volver atrás</a>
        <a href="<?= site_url('profile') ?>">Perfil</a>
        <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
    </div>
</div>

<div class="container mt-4">
    <h2>Ruta del Skate</h2>
    <div class="map-container" id="map"></div>
</div>

<footer>
    <p>&copy; 2024 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const REFRESH_INTERVAL_MS = 5000; // Aumentado a 10s para reducir carga si es necesario. (Puedes usar 5000)
    const PAGE_URL = window.location.href; 

    var map;
    var polyline;
    var startMarker;
    var endMarker;
    var initialized = false;

    /**
     * Dibuja y/o actualiza la ruta en el mapa.
     */
    function updateMap(newWaypoints) {
        if (newWaypoints.length === 0) {
            console.log("No hay waypoints para dibujar.");
            // Si el mapa ya fue inicializado (aunque sea con un centro por defecto), no hacemos nada más.
            if (!initialized) {
                 initializeDefaultMap();
            }
            return;
        }

        var firstPoint = newWaypoints[0];
        var lastPoint = newWaypoints[newWaypoints.length - 1];

        // 1. Inicialización del mapa Leaflet
        if (!initialized) {
            console.log("Inicializando mapa con la ruta inicial...");
            
            map = L.map('map').setView(firstPoint, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            polyline = L.polyline(newWaypoints, {color: '#ffcc00', weight: 5}).addTo(map);
            map.fitBounds(polyline.getBounds());
            initialized = true;

        } else {
            // 2. Actualización (solo mueve la línea y marcadores)
            
            // Actualizar polilínea
            polyline.setLatLngs(newWaypoints);

            // Ajustar la vista para mostrar la trayectoria completa
            map.fitBounds(polyline.getBounds());
        }
        
        // --- Actualización de Marcadores ---
        
        // Marcador de Inicio
        if (startMarker) {
            startMarker.setLatLng(firstPoint);
        } else {
            startMarker = L.marker(firstPoint, {
                icon: L.divIcon({
                    className: 'start-marker',
                    html: '<div style="background-color:#ffcc00; border-radius:50%; width:20px; height:20px; border:3px solid #ffb700;"></div>',
                    iconSize: [20, 20]
                })
            }).addTo(map).bindPopup("Punto de inicio");
        }
        
        // Marcador de Fin (solo si hay más de un punto)
        if (newWaypoints.length > 1) {
             if (endMarker) {
                endMarker.setLatLng(lastPoint);
            } else {
                endMarker = L.marker(lastPoint, {
                    icon: L.divIcon({
                        className: 'end-marker',
                        html: '<div style="background-color:#ff0033; border-radius:50%; width:20px; height:20px; border:3px solid #cc002a;"></div>',
                        iconSize: [20, 20]
                    })
                }).addTo(map).bindPopup("Punto final");
            }
        } else if (endMarker) {
            // Si solo hay un punto o menos, eliminamos el marcador final si existe
            map.removeLayer(endMarker);
            endMarker = null;
        }
    }

    /**
     * Inicializa un mapa vacío en una ubicación por defecto.
     */
    function initializeDefaultMap() {
        if (initialized) return; // Evitar doble inicialización
        console.log("Mapa inicializado sin ruta, esperando datos.");
        map = L.map('map').setView([-32.1880, -64.1105], 13); // Coordenada central de Río Tercero
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        initialized = true;
    }

    /**
     * Función que pide los datos al servidor de forma asíncrona (AJAX).
     */
    function fetchWaypoints() {
        fetch(PAGE_URL, {
            // Se envía un encabezado especial que PHP usa para detectar AJAX
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                updateMap(data);
            } else if (!initialized) {
                 // Si no hay datos, pero el mapa no está inicializado, lo inicializamos en un punto por defecto
                initializeDefaultMap();
            }
        })
        .catch(error => {
            console.error('Error al obtener los waypoints:', error);
            // Aseguramos que el mapa se inicialice en caso de fallo AJAX
            if (!initialized) {
                initializeDefaultMap();
            }
        })
        .finally(() => {
            // Configurar el temporizador para la próxima actualización
            setTimeout(fetchWaypoints, REFRESH_INTERVAL_MS);
        });
    }

    // --- Inicio del Script ---
    // 1. Carga inicial: Usa los datos generados por PHP en la carga de la página
    var initialWaypoints = <?php echo $waypointsJson; ?>;

    if (initialWaypoints.length > 0) {
        updateMap(initialWaypoints);
    } else {
        // Inicializar un mapa básico si no hay datos iniciales
        initializeDefaultMap();
    }

    // 2. Iniciar el ciclo de actualización automática INMEDIATAMENTE después de la carga inicial
    // Llamar directamente a fetchWaypoints() iniciará el ciclo, y la función se llama a sí misma
    // al final con setTimeout.
    fetchWaypoints();

</script>

</body>
</html>