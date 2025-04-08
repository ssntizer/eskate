<?php
// Conectar a la base de datos
$db = \Config\Database::connect();
$codigo = 'YYYYY1';

// Obtener los datos del recorrido
$query = $db->query("SELECT longitud, latitud FROM skate_tracking WHERE codigo = '$codigo' ORDER BY timestamp ASC");
$waypoints = [];

foreach ($query->getResultArray() as $row) {
    $waypoints[] = [$row['latitud'], $row['longitud']]; // Leaflet usa [latitud, longitud]
}

// Convertir los waypoints en formato JSON para usarlos en JavaScript
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
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 70px; /* Espacio para el header */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header consistente */
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
            font-family: 'Baskervville', serif;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Botones del header */
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

        /* Contenido principal */
        .container {
            margin-top: 30px;
            padding-bottom: 60px; /* Espacio para el footer */
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

        /* Mapa */
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

        /* Footer */
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

        /* Estilos para el mapa */
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

        /* Responsive */
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
        <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
    </div>
</div>

<div class="container mt-4">
    <h2>Ruta del Skate</h2>
    <div class="map-container" id="map"></div>
</div>

<footer>
    <p>&copy; 2025 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var waypoints = <?php echo $waypointsJson; ?>;
    var map = L.map('map').setView(waypoints[0], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Cambiamos el color de la línea a amarillo para que combine con el tema
    var polyline = L.polyline(waypoints, {color: '#ffcc00', weight: 5}).addTo(map);
    
    // Añadir marcador de inicio
    if(waypoints.length > 0) {
        L.marker(waypoints[0], {
            icon: L.divIcon({
                className: 'start-marker',
                html: '<div style="background-color:#ffcc00; border-radius:50%; width:20px; height:20px; border:3px solid #ffb700;"></div>',
                iconSize: [20, 20]
            })
        }).addTo(map).bindPopup("Punto de inicio");
    }
    
    // Añadir marcador de fin
    if(waypoints.length > 1) {
        L.marker(waypoints[waypoints.length-1], {
            icon: L.divIcon({
                className: 'end-marker',
                html: '<div style="background-color:#ff0033; border-radius:50%; width:20px; height:20px; border:3px solid #cc002a;"></div>',
                iconSize: [20, 20]
            })
        }).addTo(map).bindPopup("Punto final");
    }
    
    map.fitBounds(polyline.getBounds());
</script>

</body>
</html>