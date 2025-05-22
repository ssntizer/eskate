<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Skates</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Baskervville+SC&display=swap');
        
        /* Ajuste para el área segura en dispositivos móviles */
        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura ligera de asfalto */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* Añade padding superior dinámicamente para evitar el notch/barra de estado */
            padding-top: env(safe-area-inset-top); 
            /* Para que el scroll se vea bien si hay padding */
            scroll-padding-top: env(safe-area-inset-top);
        }

        .header {
            background-color: #005f87;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: sticky; /* O 'fixed' si quieres que siempre esté visible al hacer scroll */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001; 
            /* Ajusta el padding superior del header si es fixed/sticky para no superponerse */
            padding-top: calc(15px + env(safe-area-inset-top)); 
            box-sizing: border-box; /* Asegura que el padding no añada ancho/alto total inesperado */
        }

        .header h1 {
            font-size: 1.5rem;
            font-family: "Baskervville SC", static;
            margin: 0;
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


        .container {
            margin-top: 40px;
            flex: 1; 
        }

        .skate-item {
            background-color: #008dc2;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .skate-item:hover {
            background-color: #007ab8;
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .skate-item::before {
            content: url('https://image.shutterstock.com/image-vector/skateboard-wheel-icon-logo-vector-260nw-1551613316.jpg'); 
            position: absolute;
            top: -10px;
            right: -10px;
            opacity: 0.2;
            width: 80px; 
            height: auto;
            pointer-events: none; 
        }

        .skate-item h4 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .skate-item p {
            font-size: 1.2rem;
        }

        .modal-content {
            background-color: #005f87;
            color: #ffffff;
            border-radius: 15px;
        }

        .modal-header {
            background-color: #004b6b;
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .btn-primary {
            background-color: #ff6600;
            border-color: #ff6600;
            border-radius: 50px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #e65c00;
            border-color: #e65c00;
        }

        .btn-light {
            background-color: #e6b800; 
            color: #005f87;
            border-radius: 8px;
            padding: 8px 16px; 
            margin-bottom: 5px;
        }

        .btn-light:hover {
            background-color: #cc9900; 
            color: #004b6b;
        }

        .btn-danger {
            background-color: #cc002a; 
            border-color: #cc002a;
            border-radius: 8px;
            padding: 8px 16px; 
            margin-bottom: 5px;
        }

        .btn-danger:hover {
            background-color: #990020; 
            border-color: #990020;
        }

        .alert {
            margin-top: 20px;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #005f87;
            margin-top: auto; 
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
            background-color: #005f87; /* Color de fondo azul oscuro */
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); 
            z-index: 1000; 
            padding: 20px;
            /* **SOLUCIÓN APLICADA AQUÍ:** Eliminar el padding fijo adicional para evitar el desplazamiento */
            padding-top: env(safe-area-inset-top); /* Solo el área segura, sin 10px o 20px fijos */
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

<div class="header">
    <h1>Lista de Skates</h1>
    <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('/') ?>"><i class="material-icons">home</i> Inicio</a></li>
            <li><a href="<?= site_url('profile') ?>"><i class="material-icons">person</i> Perfil</a></li>
        </ul>
    </div>
    <div class="bottom-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('logout') ?>"><i class="material-icons">exit_to_app</i> Cerrar sesión</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($skates)): ?>
        <div class="row">
        <?php foreach ($skates as $skate): ?>
    <div class="col-md-4 mb-4 col-sm-6 col-12">
        <div class="skate-item" onclick="window.location.href='<?= site_url('view-skate/' . esc($skate['codigo'])) ?>'">
            <h3> <strong><?= !empty($skate['apodo']) ? esc($skate['apodo']) : esc($skate['codigo']) ?></strong></h3>
            <h5>Codigo del skate: <?= esc($skate['codigo']) ?></h5>
            <p>Batería: <?= esc($skate['bateria']) ?>%</p>
            <p>Velocidad: <?= esc($skate['velocidad']) ?> km/h</p>
            <form action="<?= site_url('unlink-skate/' . esc($skate['codigo'])) ?>" method="POST">
                <button type="submit" class="btn btn-danger">Borrar Skate</button>
            </form>
            <form action="<?= site_url('deleteapodo/' . esc($skate['codigo'])) ?>" method="POST">
                <button type="submit" class="btn btn-danger">Borrar Apodo</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
           Este usuario no tiene skates
        </div>
    <?php endif; ?>

    <button class="btn btn-light" data-toggle="modal" data-target="#addSkateModal">Agregar Skate</button>
    <button class="btn btn-light" data-toggle="modal" data-target="#apodoSkateModal">Cambiar Apodo</button>
</div>


<div class="modal fade" id="addSkateModal" tabindex="-1" role="dialog" aria-labelledby="addSkateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkateModalLabel">Vincular un skate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('add-skate') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="codigo">Código del Skate</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Skate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="apodoSkateModal" tabindex="-1" role="dialog" aria-labelledby="apodoSkateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkateModalLabel">Cambiar apodo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('update-skate-apodo/') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="codigo">Código del Skate</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                        <label for="apodo">Apodo deseado</label>
                        <input type="text" class="form-control" id="apodo" name="apodo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuIcon = document.getElementById('menuIcon');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const navItems = mobileNavOverlay.querySelectorAll('.mobile-nav-list a');

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