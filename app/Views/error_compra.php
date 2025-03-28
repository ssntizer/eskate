<!-- app/Views/error_compra.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error en Compra - ESkate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h2 class="h4 mb-0">Error en el Pago</h2>
            </div>
            <div class="card-body">
                <?php if (session('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-octagon-fill"></i> <?= session('error') ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-octagon-fill"></i> Ocurrió un error desconocido durante el proceso de pago
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?= base_url() ?>" class="btn btn-primary">
                        <i class="bi bi-house-door"></i> Volver al inicio
                    </a>
                    <a href="<?= base_url('carrito') ?>" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-cart"></i> Volver al carrito
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>