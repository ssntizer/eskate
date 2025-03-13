<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Skates</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Baskervville+SC&display=swap');

        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            font-family: "Baskervville SC", serif;
            margin: 0;
        }

        /* Estilo del botón hamburguesa */
        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: #ffffff;
        }

        /* Estilo del menú desplegable */
        .dropdown-menu {
            background-color: #005f87;
            border-radius: 8px;
        }

        .dropdown-menu a {
            color: #ffffff;
            padding: 10px 15px;
            display: block;
        }

        .dropdown-menu a:hover {
            background-color: #00719c;
        }

        .container {
            margin-top: 40px;
            flex: 1;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #005f87;
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

    </style>
</head>
<body>

<div class="header">
    <h1>Lista de Skates</h1>

    <!-- Botón menú hamburguesa -->
    <div class="dropdown">
        <button class="menu-icon btn btn-link dropdown-toggle" type="button" id="menuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            &#9776;
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="menuDropdown">
            <a class="dropdown-item" href="<?= site_url('/') ?>">Inicio</a>
            <a class="dropdown-item" href="<?= site_url('logout') ?>">Cerrar sesión</a>
            <a class="dropdown-item" href="#">Compras</a>
        </div>
    </div>
</div>

<div class="container">
    <!-- Mostrar mensajes de error y éxito -->
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
        <div class="alert alert-info">Este usuario no tiene skates</div>
    <?php endif; ?>

    <button class="btn btn-light" data-toggle="modal" data-target="#addSkateModal">Agregar Skate</button>
    <button class="btn btn-light" data-toggle="modal" data-target="#apodoSkateModal">Cambiar Apodo</button>
</div>

<!-- Modal para agregar skate -->
<div class="modal fade" id="addSkateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vincular un skate</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= site_url('add-skate') ?>" method="POST">
                <div class="modal-body">
                    <label for="codigo">Código del Skate</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Skate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para cambiar apodo -->
<div class="modal fade" id="apodoSkateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar apodo</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= site_url('update-skate-apodo/') ?>" method="POST">
                <div class="modal-body">
                    <label for="codigo">Código del Skate</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required>
                    <label for="apodo">Apodo deseado</label>
                    <input type="text" class="form-control" id="apodo" name="apodo" required>
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>