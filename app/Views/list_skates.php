<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Skates</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #00719c;
            color: #ffffff;
        }
        .header {
            background-color: #005f87; /* Un poco más oscuro que el fondo */
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .skate-item {
            background-color: #008dc2;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .skate-item:hover {
            background-color: #007ab8;
        }
        /* Estilos personalizados para el modal */
        .modal-content {
            background-color: #00719c;
            color: #ffffff;
            border-radius: 10px;
        }
        .modal-header, .modal-footer {
            border-color: #005f87;
        }
        .modal-header {
            background-color: #005f87;
        }
        .btn-primary {
            background-color: #008dc2;
            border-color: #008dc2;
        }
        .btn-primary:hover {
            background-color: #007ab8;
            border-color: #007ab8;
        }
        .btn-secondary {
            background-color: #5f6368;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Skates List</h1>
    <a href="<?= site_url('logout') ?>" class="btn btn-light">Logout</a>
</div>

<div class="container mt-5">
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
                <div class="col-md-4 mb-3">
                    <div class="skate-item" onclick="window.location.href='<?= site_url('view-skate/' . esc($skate['codigo'])) ?>'">
                        <h4>Skate Code: <?= esc($skate['codigo']) ?></h4>
                        <p><strong>Batería:</strong> <?= esc($skate['bateria']) ?>%</p>
                        <p><strong>Velocidad:</strong> <?= esc($skate['velocidad']) ?> km/h</p>
                        <!-- Formulario para borrar skate -->
                        <form action="<?= site_url('unlink-skate/' . esc($skate['codigo'])) ?>" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger">Borrar Skate</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            No skates found for this user.
        </div>
    <?php endif; ?>
    
    <!-- Botón para agregar un nuevo skate -->
    <button class="btn btn-light mb-3" data-toggle="modal" data-target="#addSkateModal">Agregar Skate</button>
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
                        <p>Insertar código del skate</p>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>