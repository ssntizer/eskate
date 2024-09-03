<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="login-form">
        <?= session()->getFlashdata('error') ?>
        <form method="post" action="<?= site_url('loginUser') ?>">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <br>
        <center>
            <a id="bl" href="<?= site_url('register') ?>">Ir a register</a>
        </center>
    </div>
</body>
</html>