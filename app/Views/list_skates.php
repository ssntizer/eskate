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
    </style>
</head>
<body>

<div class="header">
    <h1>Skates List</h1>
    <a href="<?= site_url('logout') ?>" class="btn btn-light">Logout</a>
</div>

<div class="container mt-5">
    <?php if (!empty($skates)): ?>
        <div class="row">
            <?php foreach ($skates as $skate): ?>
                <div class="col-md-4 mb-3">
                    <div class="skate-item" onclick="window.location.href='<?= site_url('view-skate/' . esc($skate['codigo'])) ?>'">
                        <h4>Skate Code: <?= esc($skate['codigo']) ?></h4>
                        <p><strong>Batería:</strong> <?= esc($skate['bateria']) ?>%</p>
                        <p><strong>Velocidad:</strong> <?= esc($skate['velocidad']) ?> km/h</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            No skates found for this user.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
