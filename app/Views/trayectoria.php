<?php
// Conectar a la base de datos
$db = \Config\Database::connect();

// Obtener el código desde la URL
$codigo = $_GET['codigo']; // Usa el código de la URL o un valor por defecto

// Obtener los datos del recorrido
$query = $db->query("SELECT longitud, latitud FROM skate_tracking WHERE codigo = ?", [$codigo]);
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
    <title>Trayectoria del Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');

        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: "Baskervville SC", serif;
            background-image: url('https://example.com/skate-pattern.png'), url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            background-size: cover, auto;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .header {
            background-color: #005f87;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
        }

        .header h1 {
            font-size: 1.5rem;
            margin: 0;
            text-align: center;
        }

        .map-container {
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 20px;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #005f87;
            width: 100%;
            margin-top: auto;
        }

        footer p {
            font-size: 0.8rem;
            color: #ffffff;
            margin: 0;
        }

        .btn-light {
            background-color: #ffcc00;
            color: #005f87;
            border-radius: 50px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Bienvenido, <?= session()->get('username') ?>!</h1>
    <div>
        <a href="<?= site_url('logout') ?>" class="btn btn-light">Cerrar sesión</a>
        <a href="javascript:history.back()" class="btn btn-light ml-2">Volver atrás</a>
    </div>
</div>

<div class="container mt-4">
    <h2 class="text-center">Ruta del Skate</h2>
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

    var polyline = L.polyline(waypoints, {color: 'red'}).addTo(map);
    map.fitBounds(polyline.getBounds());
</script>

</body>
</html>
