<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Skates | E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap');
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 70px;
            min-height: 100vh; /* Asegura que el body ocupe al menos toda la altura de la vista */
            display: flex; /* Habilita Flexbox */
            flex-direction: column; /* Apila los elementos hijos verticalmente */
        }

        /* Header */
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
            font-size: 1.8rem;
            font-family: "Quicksand", sans-serif;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Contenedor flexible para botones de header y botón de perfil */
        .header-right {
            display: flex;
            align-items: center; /* Alinea verticalmente los elementos */
            gap: 20px; /* Espacio entre header-buttons y profile-button */
        }


        /* Botones del header */
        .header-buttons {
            display: flex;
            gap: 15px; /* Espacio entre los botones dentro de este grupo */
        }

        .header-buttons a {
            color: #333;
            text-decoration: none;
            font-size: 1rem;
            padding: 8px 20px;
            border-radius: 50px;
            background-color: #ffcc00;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: inline-block; /* Asegura que el padding y width/height funcionen */
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

        /* Estilo para el botón de perfil */
        .profile-button {
            display: flex; /* Permite centrar el icono */
            align-items: center;
            justify-content: center;
            background-color: #ffcc00; /* Color de fondo amarillo */
            color: #333; /* Color del icono oscuro */
            width: 40px; /* Ancho fijo para hacerlo redondo */
            height: 40px; /* Alto fijo para hacerlo redondo */
            border-radius: 50%; /* Lo hace redondo */
            text-decoration: none; /* Quita el subrayado */
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra sutil */
            font-size: 1.2rem; /* Tamaño del icono */
            flex-shrink: 0; /* Evita que se encoja en pantallas pequeñas */
        }

        .profile-button:hover {
            background-color: #ffb700; /* Amarillo más oscuro al pasar el ratón */
            color: #333;
            transform: scale(1.1); /* Efecto de escala */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Contenedor principal del contenido */
        .main-content-wrapper {
            flex-grow: 1; /* Permite que este contenedor ocupe el espacio restante */
            /* No necesita margin-top aquí si el padding-top del body ya lo maneja */
        }


        /* Contenedor de Bootstrap */
        .container {
            margin-top: 40px; /* Espacio debajo del header fijo */
            padding-bottom: 60px; /* Espacio antes del footer */
        }


        /* Tarjetas de skates - Eliminada la línea amarilla superior */
        .skate-item {
            background-color: #005f87;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid #004b6b;
            cursor: pointer;
        }

        .skate-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
            border-color: #ffcc00;
            /* Efecto de brillo alrededor */
            outline: 2px solid rgba(255, 204, 0, 0.5);
            outline-offset: 2px;
        }

        .skate-item h3 {
            color: #ffcc00;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-family: 'Baskervville', serif;
        }

        .skate-item h5,
        .skate-item p {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        /* Botones dentro de las tarjetas */
        .skate-item form {
            margin-top: 10px; /* Espacio entre formularios si hay varios */
            margin-bottom: 5px; /* Espacio debajo del formulario */
        }

        .skate-item form:last-child {
            margin-bottom: 0; /* No margin bottom en el último formulario */
        }

        .skate-item .btn-danger {
            background-color: #ff0033;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%; /* Ocupa todo el ancho disponible */
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .skate-item .btn-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #cc002a;
            transition: width 0.3s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .skate-item .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 0, 51, 0.4);
        }

        .skate-item .btn-danger:hover::before {
            width: 100%;
        }

        /* Botones principales */
        .btn-main {
            background-color: #ffcc00;
            color: #333;
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            margin-right: 15px;
            margin-bottom: 15px;
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
        }

        .btn-main:hover::before {
            width: 100%;
        }

        /* Alertas */
        .alert {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background-color: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
        }

        .alert-success {
            background-color: rgba(107, 255, 107, 0.2);
            color: #6bff6b;
        }

        .alert-info {
            background-color: rgba(0, 191, 255, 0.2);
            color: #00bfff;
        }

        /* Modales */
        .modal-content {
            background-color: #005f87;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            border-radius: 15px;
            border: 1px solid #004b6b;
            color: #ffffff;
        }

        .modal-header {
            border-bottom: 1px solid #004b6b;
        }

        .modal-title {
            color: #ffcc00;
            font-family: 'Baskervville', serif;
        }

        .close {
            color: #ffffff;
            opacity: 0.8;
        }

        .close:hover {
            color: #ffcc00;
            opacity: 1;
        }

        .modal-body input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            margin-bottom: 15px;
            color: #333; /* Color del texto dentro del input */
        }

        .modal-body input::placeholder {
            color: #666; /* Color del placeholder */
        }


        .modal-body input:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        .modal-footer {
            border-top: 1px solid #004b6b;
        }

        /* Footer - Ajustado para ser sticky */
        footer {
            background-color: #004b6b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            border-top: 3px solid #005f87;
            margin-top: auto; /* Esto empuja el footer hacia abajo en un flex container */
            flex-shrink: 0; /* Evita que el footer se encoja */
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
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .header-right {
                gap: 10px; /* Reduce el espacio entre botones y perfil en tablet */
            }

            .header-buttons a {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .profile-button {
                width: 35px; /* Ligeramente más pequeño */
                height: 35px;
                font-size: 1rem;
            }

            .skate-item {
                padding: 20px 15px;
            }

            .skate-item h3 {
                font-size: 1.3rem;
            }

            .skate-item h5,
            .skate-item p {
                font-size: 1rem;
            }

            .btn-main {
                padding: 10px 20px;
                font-size: 1rem;
                margin-right: 10px;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 576px) {
            .header {
                flex-direction: column;
                padding: 10px;
                gap: 10px; /* Espacio al apilar elementos del header */
            }

            .header h1 {
                margin-bottom: 5px; /* Ajusta el margen */
            }

            .header-right {
                flex-direction: column; /* Apila los botones y el botón de perfil */
                width: 100%; /* Ocupa todo el ancho */
                gap: 10px; /* Espacio entre los elementos apilados */
                align-items: center; /* Centra los elementos apilados */
            }

            .header-buttons {
                width: 100%;
                justify-content: space-around; /* Distribuye los botones internos */
                gap: 5px; /* Reduce el espacio entre botones apilados si se envuelven */
            }

            .skate-item {
                padding: 15px;
            }

            .skate-item h3 {
                font-size: 1.2rem;
            }

            .skate-item h5,
            .skate-item p {
                font-size: 0.9rem;
            }

            .btn-main {
                width: auto; /* Permite que los botones se ajusten al contenido */
                display: inline-block; /* Asegura que se muestren en línea */
                margin-right: 5px; /* Reduce el margen */
                margin-bottom: 5px; /* Reduce el margen */
                font-size: 0.9rem;
                padding: 8px 15px;
            }

            .profile-button {
                width: 30px; /* Aún más pequeño */
                height: 30px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<script src="https://kit.fontawesome.com/releases/v6.5.1/js/all.js" crossorigin="anonymous"></script>


<div class="header">
    <h1><a href="<?= site_url('/') ?>" style="text-decoration: none; color: inherit;">Lista de Skates</a></h1>

    <div class="header-right">
        <div class="header-buttons">
            <?php if(session()->get('logged_in')): ?>
                <a href="<?= site_url('/') ?>">Inicio</a>
                <a href="<?= site_url('profile') ?>">Perfil</a>
                <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
            <?php else: ?>
                <a href="<?= site_url('login') ?>">Ingresar</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="main-content-wrapper">
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
                            <h3><strong><?= !empty($skate['apodo']) ? esc($skate['apodo']) : esc($skate['codigo']) ?></strong></h3>
                            <h5>Código del skate: <?= esc($skate['codigo']) ?></h5>
                            <p>Batería: <?= esc($skate['bateria']) ?>%</p>
                            <p>Velocidad: <?= esc($skate['velocidad']) ?> km/h</p>
                            <form action="<?= site_url('unlink-skate/' . esc($skate['codigo'])) ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres desvincular este skate?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger">Borrar Skate</button>
                            </form>
                            <?php if(!empty($skate['apodo'])): ?>
                                <form action="<?= site_url('deleteapodo/' . esc($skate['codigo'])) ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres borrar el apodo de este skate?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger">Borrar Apodo</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Este usuario no tiene skates
            </div>
        <?php endif; ?>

        <button class="btn btn-main" data-toggle="modal" data-target="#addSkateModal">Agregar Skate</button>
        <?php if (!empty($skates)): ?>
            <button class="btn btn-main" data-toggle="modal" data-target="#apodoSkateModal">Cambiar Apodo</button>
        <?php endif; ?>
    </div>
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
                    <button type="submit" class="btn btn-main">Guardar Skate</button>
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
                    <button type="submit" class="btn btn-main">Guardar cambios</button>
                </div>
            </form>
        </div>
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