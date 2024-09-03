<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #2e5c7a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Estilos del formulario */
        .login-form {
            width: 350px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
            text-align: center;
        }

        /* Estilos del mensaje de bienvenida */
        .login-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Estilos de los campos de entrada */
        .login-form input[type="email"], .login-form input[type="password"] {
            width: 90%;
            height: 40px;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Estilos de los botones */
        .login-form button[type="submit"] {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }

        /* Efectos de hover en los botones */
        .login-form button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Estilos de error */
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Estilos específicos para el enlace "Ir a registro" */
        .login-form a#bl {
            width: 95%;
            height: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }

        /* Efectos de hover para el enlace "Ir a registro" */
        .login-form a#bl:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="login-form">
        <h2>Es bueno verte de vuelta, Inicia sesion</h2>
        <?= session()->getFlashdata('error') ?>
        <form method="post" action="<?= site_url('loginUser') ?>">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <a id="bl" href="<?= site_url('register') ?>">Registrarse</a>
    </div>
</body>
</html>