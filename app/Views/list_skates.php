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
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura ligera de asfalto */
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
            font-size: 2rem;
            font-family: "Baskervville SC", static;
            margin: 0;
        }

        .container {
            margin-top: 40px;
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
            background-color: #ffcc00;
            color: #005f87;
            border-radius: 50px;
        }

        .btn-light:hover {
            background-color: #ffb700;
            color: #004b6b;
        }

        .btn-danger {
            background-color: #ff0033;
            border-color: #ff0033;
            margin-top: 10px;
            border-radius: 50px;
        }

        .btn-danger:hover {
            background-color: #cc002a;
            border-color: #cc002a;
        }

        .alert {
            margin-top: 20px;
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

    </style>
</head>
<body>

<div class="header">
    <h1>Lista de Skates</h1>
    <a href="<?= site_url('logout') ?>" class="btn btn-light">Cerrar sesión</a>
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
                <div class="col-md-4 mb-4">
                    <div class="skate-item" onclick="window.location.href='<?= site_url('view-skate/' . esc($skate['codigo'])) ?>'">
                        <h4>Codigo del skate: <?= esc($skate['codigo']) ?></h4>
                        <p><strong>Batería:</strong> <?= esc($skate['bateria']) ?>%</p>
                        <p><strong>Velocidad:</strong> <?= esc($skate['velocidad']) ?> km/h</p>
                        <form action="<?= site_url('unlink-skate/' . esc($skate['codigo'])) ?>" method="POST">
                            <button type="submit" class="btn btn-danger">Borrar Skate</button>
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
</div>

<!-- Modal para agregar skate -->
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

<footer>
    <p>&copy; 2024 E-skate - Diseñado para la accion - Contactanos al eskatevz@gmail.com </p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>