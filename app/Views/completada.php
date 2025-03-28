<!-- app/Views/completada.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Completada - ESkate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h2 class="h4 mb-0">¡Pago Completado!</h2>
            </div>
            <div class="card-body">
                <?php if (session('success')): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i> <?= session('success') ?>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="h5">Detalles de la transacción:</h3>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>ID de Transacción:</strong> 
                                <?= session('paypal_data')['id'] ?? 'N/A' ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Monto:</strong> 
                                $<?= session('paypal_data')['transactions'][0]['amount']['total'] ?? '0.00' ?> USD
                            </li>
                            <li class="list-group-item">
                                <strong>Estado:</strong> 
                                <span class="badge bg-success"><?= session('paypal_data')['state'] ?? 'completado' ?></span>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i> No se encontraron datos de la transacción
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?= base_url() ?>" class="btn btn-primary">
                        <i class="bi bi-house-door"></i> Volver al inicio
                    </a>
                    <a href="<?= base_url('mis-compras') ?>" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-receipt"></i> Ver mis compras
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php if (ENVIRONMENT !== 'production'): ?>
<div class="mt-4 p-3 bg-light rounded">
    <h4>Datos de depuración:</h4>
    <pre><?= print_r(session()->get(), true) ?></pre>
</div>
<?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>