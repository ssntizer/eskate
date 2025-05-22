<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Baskervville+SC&display=swap');

        /* Ajuste para el área segura en dispositivos móviles */
        body {
            background-color: #00719c; /* Color de fondo de la página */
            color: #ffffff; /* Color del texto */
            font-family: 'Montserrat', sans-serif; /* Usamos Montserrat como en la otra vista */
            background-image: url('https://example.com/skate-pattern.png'), url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura ligera de asfalto */
            background-size: cover, auto;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            /* Añade padding superior dinámicamente para evitar el notch/barra de estado */
            padding-top: env(safe-area-inset-top);
            /* Para que el scroll se vea bien si hay padding */
            scroll-padding-top: env(safe-area-inset-top);
        }

        .header {
            background-color: #005f87; /* Color de fondo del encabezado */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: sticky; /* O 'fixed' si quieres que siempre esté visible al hacer scroll */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001; /* El header se mantiene en este z-index para estar por encima del contenido principal */
            /* Ajusta el padding superior del header si es fixed/sticky para no superponerse */
            padding-top: calc(15px + env(safe-area-inset-top));
            box-sizing: border-box; /* Asegura que el padding no añada ancho/alto total inesperado */
        }

        .header h1 {
            font-size: 1.5rem;
            font-family: "Baskervville SC", static;
            margin: 0;
            /* text-align: center; Lo quitamos porque el menú lo desplaza a la izquierda */
        }

        /* MEDIA QUERY para ajustar el título del header y su altura solo en pantallas pequeñas */
        @media (max-width: 767.98px) { /* Bootstrap's 'md' breakpoint */
            .header {
                /* Aumentamos el padding inferior del header para hacerlo un poco más alto */
                padding-bottom: 25px; /* Valor ajustado para que el título se vea mejor */
            }
            .header h1 {
                /* Ajustar el tamaño de fuente para que quepa mejor si es necesario */
                font-size: 1.4rem;
                /* Aseguramos que el título no quede bajo el notch en móviles, si es necesario */
                margin-top: env(safe-area-inset-top);
            }
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

        /* ESTILOS DEL MENÚ HAMBURGUESA PERSONALIZADO (copiados de list_skates.php) */
        .menu-icon {
            background-color: #005f87;
            color: white;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            z-index: 1002; /* Mantener este alto para que el icono del menú sea clickeable */
            position: fixed; /* Ojo: esto lo pondrá fijo en la ventana, no en el header si el header no es fijo */
            top: calc(15px + env(safe-area-inset-top));
            right: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .menu-icon:hover {
            background-color: #004b6b;
            transform: scale(1.05);
        }

        .menu-icon i {
            font-size: 28px;
        }

        .mobile-nav-overlay {
            position: fixed;
            top: 0; /* Esto será ajustado por JavaScript */
            right: -100vw;
            width: min(75vw, 300px);
            height: 100vh; /* Esto también se ajustará dinámicamente */
            background-color: #005f87; /* Color de fondo azul oscuro */
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 999; /* Z-index más bajo que el header (1001) */
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        .mobile-nav-overlay.is-open {
            right: 0;
        }

        .mobile-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-list li {
            margin-bottom: 10px;
        }

        .mobile-nav-list a {
            display: block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
        }

        .mobile-nav-list a:hover {
            background-color: #00719c;
            color: #ffcc00;
        }

        .mobile-nav-list a i.material-icons {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .mobile-nav-overlay .top-links {
            margin-bottom: auto;
            padding-top: 15px; /* Un poco de padding para separar del borde superior del menú */
        }

        .mobile-nav-overlay .bottom-links {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        body.menu-active {
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="header" id="mainHeader"> <h1>Bienvenido, <?= session()->get('username') ?>!</h1>
    <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('/') ?>"><i class="material-icons">home</i> Inicio</a></li>
            <li><a href="<?= site_url('profile') ?>"><i class="material-icons">person</i> Perfil</a></li>
            <li><a href="javascript:history.back()"><i class="material-icons">arrow_back</i> Volver atrás</a></li> </ul>
    </div>
    <div class="bottom-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('logout') ?>"><i class="material-icons">exit_to_app</i> Cerrar sesión</a></li> </ul>
    </div>
</nav>

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
<br>
<center><div>
<a href="<?= site_url('trayectoria') ?>" class="btn btn-light">Ver trayectoria</a>
    </div></center>
<footer>
    <p>© 2025 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuIcon = document.getElementById('menuIcon');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const mainHeader = document.getElementById('mainHeader'); // Obtenemos el header
        const navItems = mobileNavOverlay.querySelectorAll('.mobile-nav-list a');

        // Función para ajustar la posición y altura del overlay
        function adjustOverlayPosition() {
            if (mainHeader && mobileNavOverlay) {
                const headerHeight = mainHeader.offsetHeight; // Obtiene la altura total del header
                mobileNavOverlay.style.top = `${headerHeight}px`; // Posiciona el overlay debajo del header
                mobileNavOverlay.style.height = `calc(100vh - ${headerHeight}px)`; // Ajusta la altura del overlay
            }
        }

        // Ejecutar al cargar y al redimensionar la ventana
        adjustOverlayPosition();
        window.addEventListener('resize', adjustOverlayPosition);

        if (menuIcon && mobileNavOverlay) {
            menuIcon.addEventListener('click', () => {
                const isOpen = mobileNavOverlay.classList.toggle('is-open');
                document.body.classList.toggle('menu-active');

                // Cambiar el ícono de hamburguesa a cruz y viceversa
                menuIcon.querySelector('i').textContent = isOpen ? 'close' : 'menu';
            });

            // Cerrar menú al hacer clic en un enlace del menú
            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    mobileNavOverlay.classList.remove('is-open');
                    document.body.classList.remove('menu-active');
                    menuIcon.querySelector('i').textContent = 'menu';
                });
            });

            // Cerrar menú al hacer clic fuera del menú
            document.body.addEventListener('click', (event) => {
                if (mobileNavOverlay.classList.contains('is-open') &&
                    !mobileNavOverlay.contains(event.target) &&
                    !menuIcon.contains(event.target)) {

                    mobileNavOverlay.classList.remove('is-open');
                    document.body.classList.remove('menu-active');
                    menuIcon.querySelector('i').textContent = 'menu';
                }
            });
        }
    });
</script>

</body>
</html>