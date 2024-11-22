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
            min-height: 100vh;
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
            font-size: 1.5rem;
            font-family: "Baskervville SC", static;
            margin: 0;
            text-align: center;
        }

        .side-panel {
            background-color: #008dc2; /* Color de fondo del panel lateral */
            padding: 20px;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .side-panel h4 {
            text-align: center;
            font-size: 1.3rem;
        }

        .map-container {
            width: 100%; /* Ancho completo */
            height: 300px; /* Altura del contenedor del mapa ajustado para móviles */
            border-radius: 15px; /* Bordes redondeados */
            overflow: hidden; /* Ocultar desbordamiento */
        }

        /* Footer con estilo dinámico */
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
            font-size: 0.9rem;
        }

        @media (min-width: 768px) {
            .header h1 {
                font-size: 2rem;
                text-align: left;
            }
            
            .map-container {
                height: 500px;
            }
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

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="side-panel">
                <h4>Información del Skate</h4>
                <hr>
                <p><strong>Batería:</strong> <?= $skate['bateria'] ?>%</p>
                <p><strong>Velocidad:</strong> <?= $skate['velocidad'] ?> km/h</p>
                <p><strong>Temperatura:</strong> <?= $skate['temperatura'] ?>°C</p>
                <p><strong>Hora de Ubicación:</strong> <?= $skate['hora'] ?></p>
            
    </button>
</form>
    </button>
</form>
    </button>
</form>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="map-container">
                <a href="https://www.google.com/maps?q=<?= $skate['latitud'] ?>,<?= $skate['longitud'] ?>" target="_blank">
                    <iframe 
                        src="https://maps.google.com/maps?q=<?= $skate['latitud'] ?>,<?= $skate['longitud'] ?>&z=15&output=embed" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </a>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>