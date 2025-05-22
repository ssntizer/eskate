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
    <title>Trayectoria del Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Baskervville+SC&display=swap');

        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            background-image: url('https://example.com/skate-pattern.png'), url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            background-size: cover, auto;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding-top: env(safe-area-inset-top);
            scroll-padding-top: env(safe-area-inset-top);
        }

        .header {
            background-color: #005f87;
            padding: 15px; /* Padding base */
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            /* **MODIFICADO/REVISADO:** Ajusta el padding superior del header. */
            padding-top: calc(15px + env(safe-area-inset-top));
            box-sizing: border-box;
        }

        .header h1 {
            font-size: 1.5rem;
            font-family: "Baskervville SC", static;
            margin: 0; /* **MODIFICADO/REVISADO:** Aseguramos que no haya margin por defecto aquí. */
            line-height: 1; /* Aseguramos que el line-height no empuje el texto */
        }

        /* MEDIA QUERY para ajustar el título del header y su altura solo en pantallas pequeñas */
        @media (max-width: 767.98px) {
            .header {
                padding-bottom: 25px;
            }
            .header h1 {
                font-size: 1.4rem;
                /* Eliminamos el margin-top de aquí, ya que el padding-top del header lo maneja */
                margin-top: 0; /* **MODIFICADO:** Aseguramos que no haya margin-top aquí. */
            }
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

        /* ESTILOS DEL MENÚ HAMBURGUESA PERSONALIZADO */
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
            z-index: 1002;
            position: fixed;
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
            top: 0;
            right: -100vw;
            width: min(75vw, 300px);
            height: 100vh;
            background-color: #005f87;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 999;
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
            padding-top: 15px;
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

<div class="header" id="mainHeader">
    <h1>Bienvenido, <?= session()->get('username') ?>!</h1>
    <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('/') ?>"><i class="material-icons">home</i> Inicio</a></li>
            <li><a href="<?= site_url('profile') ?>"><i class="material-icons">person</i> Perfil</a></li>
            <li><a href="javascript:history.back()"><i class="material-icons">arrow_back</i> Volver atrás</a></li>
        </ul>
    </div>
    <div class="bottom-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('logout') ?>"><i class="material-icons">exit_to_app</i> Cerrar sesión</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Ruta del Skate</h2>
    <div class="map-container" id="map"></div>
</div>

<footer>
    <p>&copy; 2025 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var waypoints = <?php echo $waypointsJson; ?>;
        if (waypoints.length > 0) {
            var map = L.map('map').setView(waypoints[0], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var polyline = L.polyline(waypoints, {color: 'red'}).addTo(map);
            map.fitBounds(polyline.getBounds());
        } else {
            document.getElementById('map').innerHTML = '<p class="text-center text-white">No hay datos de trayectoria disponibles para este skate.</p>';
            const mapContainer = document.getElementById('map');
            if (mapContainer) {
                mapContainer.style.display = 'flex';
                mapContainer.style.alignItems = 'center';
                mapContainer.style.justifyContent = 'center';
                mapContainer.style.height = '200px';
            }
        }

        const menuIcon = document.getElementById('menuIcon');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const mainHeader = document.getElementById('mainHeader');
        const navItems = mobileNavOverlay.querySelectorAll('.mobile-nav-list a');

        function adjustOverlayPosition() {
            if (mainHeader && mobileNavOverlay) {
                const headerHeight = mainHeader.offsetHeight;
                mobileNavOverlay.style.top = `${headerHeight}px`;
                mobileNavOverlay.style.height = `calc(100vh - ${headerHeight}px)`;
            }
        }

        adjustOverlayPosition();
        window.addEventListener('resize', adjustOverlayPosition);

        if (menuIcon && mobileNavOverlay) {
            menuIcon.addEventListener('click', () => {
                const isOpen = mobileNavOverlay.classList.toggle('is-open');
                document.body.classList.toggle('menu-active');
                menuIcon.querySelector('i').textContent = isOpen ? 'close' : 'menu';
            });

            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    mobileNavOverlay.classList.remove('is-open');
                    document.body.classList.remove('menu-active');
                    menuIcon.querySelector('i').textContent = 'menu';
                });
            });

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