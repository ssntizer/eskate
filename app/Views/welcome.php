<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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

        /* Panel lateral */
        .side-panel {
            background-color: #005f87;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            border: 1px solid #004b6b;
            position: relative;
            overflow: hidden;
        }

        .side-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .side-panel h4 {
            text-align: center;
            font-size: 1.5rem;
            color: #ffcc00;
            font-family: 'Baskervville', serif;
            margin-bottom: 20px;
            position: relative;
        }

        .side-panel h4::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
        }

        .side-panel p {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .side-panel strong {
            color: #ffcc00;
        }

        /* Mapa */
        .map-container {
            width: 100%;
            height: 300px;
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
            z-index: 1;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Botón de trayectoria */
        .trayectoria-btn {
            text-align: center;
            margin: 20px 0;
        }

        .btn-main {
            background-color: #ffcc00;
            color: #333;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-decoration: none;
            display: inline-block;
        }

        .btn-main::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700;
            transition: width 0.4s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .btn-main:hover {
            color: #333;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
            text-decoration: none;
        }

        .btn-main:hover::before {
            width: 100%;
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

        /* Responsive */
        @media (min-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .map-container {
                height: 500px;
            }
            
            .header-buttons a {
                font-size: 1rem;
                padding: 10px 25px;
            }
        }

        @media (max-width: 576px) {
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
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="side-panel">
                <h4>Información del Skate</h4>
                <p><strong>Batería:</strong> <?= $skate['bateria'] ?>%</p>
                <p><strong>Velocidad:</strong> <?= $skate['velocidad'] ?> km/h</p>
                <p><strong>Temperatura:</strong> <?= $skate['temperatura'] ?>°C</p>
                <p><strong>Hora de Ubicación:</strong> <?= $skate['hora'] ?></p>
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
    
    <div class="trayectoria-btn">
        <a href="<?= site_url('trayectoria') ?>" class="btn btn-main">Ver trayectoria</a>
    </div>
</div>

<footer>
    <p>&copy; 2025 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>