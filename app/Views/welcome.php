<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');

        body {
            background-color: #00719c; /* Color de fondo de la página */
            color: #ffffff; /* Color del texto */
            font-family: "Baskervville SC", static;
            background-image: url('https://example.com/skate-pattern.png'), url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura ligera de asfalto */
            background-size: cover, auto;
            background-position: center;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        .header {
            background-color: #005f87; /* Color de fondo del encabezado */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
        }

        .header h1 {
            font-size: 2rem;
            font-family: "Baskervville SC", static;
            margin: 0;
        }

        .side-panel {
            background-color: #008dc2; /* Color de fondo del panel lateral */
            padding: 20px;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .side-panel h4,
        .side-panel p {
            margin: 0; /* Sin margen */
            padding: 5px 0; /* Espaciado interno */
        }

        .map-container {
            width: 100%; /* Ancho completo */
            height: 500px; /* Altura del contenedor del mapa */
            border-radius: 15px; /* Bordes redondeados */
            overflow: hidden; /* Ocultar desbordamiento */
        }

        /* Footer con estilo dinámico */
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #005f87;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        footer p {
            font-size: 0.9rem;
            color: #ffffff;
            margin: 0;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
        }

        footer a:hover {
            color: #ffb700;
        }
        .btn-light {
            background-color: #ffcc00;
            color: #005f87;
            border-radius: 50px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Bienvenido, <?= session()->get('username') ?>!</h1>
    <a href="<?= site_url('logout') ?>" class="btn btn-light">Cerrar sesión</a>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="side-panel">
                <h4 class="text-center">Información del Skate</h4>
                <hr>
                <div class="d-flex flex-column">
                    <p><strong>Batería:</strong> <?= $skate['bateria'] ?>%</p>
                    <p><strong>Velocidad:</strong> <?= $skate['velocidad'] ?> km/h</p>
                    <p><strong>Temperatura:</strong> <?= $skate['temperatura'] ?>°C</p>
                    <p><strong>Hora de Ubicación:</strong> <?= $skate['hora'] ?></p>
                </div>
            </div>        
        </div>
        <div class="col-md-8">
            <div class="map-container">
                <a href="https://www.google.com/maps?q=<?= $skate['longitud'] ?>,<?= $skate['latitud'] ?>" target="_blank">
                    <iframe 
                        src="https://maps.google.com/maps?q=<?= $skate['longitud'] ?>,<?= $skate['latitud'] ?>&z=15&output=embed" 
                        width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </a>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 E-skate - Diseñado para la acción - Contáctanos al E-skate@gmail.com</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>