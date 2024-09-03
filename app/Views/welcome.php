<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #00719c;
            color: #ffffff;
        }
        .header {
            background-color: #005f87;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .side-panel {
            background-color: #008dc2;
            padding: 20px;
            border-radius: 10px;
        }
        .side-panel h4,
        .side-panel p {
            margin: 0;
            padding: 5px 0;
        }
        .map-container {
            width: 100%;
            height: 500px;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome, <?= session()->get('username') ?>!</h1>
    <a href="<?= site_url('logout') ?>" class="btn btn-light">Logout</a>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="side-panel">
                <h4 class="text-center">Skate Information</h4>
                <hr>
                <div class="d-flex flex-column">
                    <p><strong>Batería:</strong> <?= $skate['bateria'] ?>%</p>
                    <p><strong>Velocidad:</strong> <?= $skate['velocidad'] ?> km/h</p>
                    <p><strong>Temperatura:</strong> <?= $skate['temperatura'] ?>°C</p>
                    <p><strong>Hora de Ubicación:</strong> <?= $skate['hora'] ?></p>
                </div>
            </div>        
        </div>
        <div class="col-md-8">
            <div class="map-container">
                <a href="https://www.google.com/maps?q=<?= $skate['longitud'] ?>,<?= $skate['latitud'] ?>" target="_blank">
                    <iframe 
                        src="https://maps.google.com/maps?q=<?= $skate['longitud'] ?>,<?= $skate['latitud'] ?>&z=15&output=embed" 
                        width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
