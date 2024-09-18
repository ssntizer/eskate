<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Skate: Revoluciona tu Movimiento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        /* Estilos generales */
        body {
            background-color: #00719c; /* Fondo de la página */
            color: #ffffff; /* Texto blanco */
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            background-color: #005f87; /* Color de fondo del encabezado */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: fixed; /* Fija el header en la parte superior */
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000; /* Asegura que esté siempre sobre el contenido */
        }

        .header h1 {
            color: #ffcc00;
            margin: 0;
            font-size: 1.5rem;
        }

        .header a {
            color: #ffcc00;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .header a:hover {
            color: #ffb700;
        }

        /* Estilos generales del contenido */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://example.com/hero-image.jpg'); /* Imagen con superposición */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); /* Sombra */
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); /* Sombra en el texto */
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .btn-main {
            background-color: #ffcc00;
            color: #333;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3); /* Sombra suave */
        }

        .btn-main:hover {
            background-color: #ffb700;
            transform: scale(1.05); /* Efecto de hover dinámico */
        }

        /* Ajuste para no solapar con el header */
        .content {
            margin-top: 80px; /* Espacio suficiente para que no quede debajo del header */
        }

        /* Sección de presentación */
        .presentation {
            padding: 60px 0;
            background-color: #005f87;
            text-align: center;
        }

        .presentation h2 {
            font-size: 2.5rem;
            color: #ffcc00;
            margin-bottom: 30px;
        }

        .presentation p {
            font-size: 1.2rem;
            color: #ffffff;
        }

        /* Sección de características */
        .features {
            padding: 50px 0;
            background-color: #005f87;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
        }

        .features h2 {
            text-align: center;
            margin-bottom: 50px;
            color: #ffcc00;
            font-size: 2.5rem;
        }

        .features .feature {
            text-align: center;
            margin-bottom: 30px;
            background-color: #008dc2;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.1); /* Sombra en los bloques */
            transition: transform 0.3s ease; /* Transición suave */
        }

        .features .feature:hover {
            transform: translateY(-10px); /* Efecto de elevación al hacer hover */
        }

        .features .feature img {
            width: 120px;
            margin-bottom: 20px;
            border-radius: 50%;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); /* Sombra en imágenes */
        }

        /* Footer */
        footer {
            background-color: #004b6b;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #ffb700;
        }

    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>E-Skate</h1>
    <div>
        <a href="#quienes-somos">¿Quiénes Somos?</a>
        <a href="#contactanos">Contáctanos</a>
        <a href="<?= site_url('logout') ?>" class="btn btn-light">Cerrar sesión</a>
    </div>
</div>

<!-- Sección Hero -->
<section class="hero">
    <div class="container content">
        <h1>Revoluciona tu Movimiento</h1>
        <p>Explora el futuro del transporte con nuestras innovadoras e-skates.</p>
        <a href="<?= site_url('login') ?>" class="btn btn-main">Inicia Sesión para Monitorear tu Skate</a>
    </div>
</section>

<!-- Sección de presentación -->
<section id="quienes-somos" class="presentation">
    <div class="container">
        <h2>Conoce la Nueva Generación de E-Skates</h2>
        <p>Innovación, rendimiento y durabilidad como nunca antes.</p>
    </div>
</section>

<!-- Sección de características -->
<section class="features">
    <div class="container">
        <h2>¿Por qué elegir E-Skate?</h2>
        <div class="row">
            <div class="col-md-4 feature">
                <img src="https://example.com/feature1.png" alt="Rendimiento">
                <h4>Máximo Rendimiento</h4>
                <p>Nuestra tecnología de batería y motores te lleva más lejos, más rápido.</p>
            </div>
            <div class="col-md-4 feature">
                <img src="https://example.com/feature2.png" alt="Durabilidad">
                <h4>Alta Durabilidad</h4>
                <p>Diseñados para resistir, sin importar el terreno.</p>
            </div>
            <div class="col-md-4 feature">
                <img src="https://example.com/feature3.png" alt="Conectividad">
                <h4>Conectividad Inteligente</h4>
                <p>Monitorea tu e-skate desde cualquier lugar con nuestra app.</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección de contacto -->
<section id="contactanos" class="presentation">
    <div class="container">
        <h2>Contáctanos</h2>
        <p>Estamos aquí para ayudarte. Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos.</p>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4