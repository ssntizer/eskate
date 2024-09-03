<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #2e5c7a; /* Color de fondo de la página */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura completa de la ventana */
            margin: 0;
        }

        /* Estilos del formulario */
        .register-form {
            width: 350px; /* Ancho del formulario */
            padding: 20px; /* Espaciado interno */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra */
            background-color: #f8f9fa; /* Color de fondo del formulario */
            text-align: center; /* Alinear texto al centro */
        }

        /* Estilos del mensaje de éxito */
        .register-form h2 {
            margin-bottom: 20px; /* Margen inferior */
            color: #333; /* Color del texto */
        }

        /* Estilos de los campos de entrada */
        .register-form input[type="text"], .register-form input[type="email"], .register-form input[type="password"] {
            width: 90%; /* Ancho del campo de entrada */
            height: 40px; /* Altura del campo de entrada */
            margin-bottom: 20px; /* Margen inferior */
            padding: 10px; /* Espaciado interno */
            border: 1px solid #ccc; /* Borde del campo */
            border-radius: 5px; /* Bordes redondeados */
        }

        /* Estilos del botón de envío */
        .register-form button[type="submit"] {
            width: 100%; /* Ancho completo */
            height: 40px; /* Altura del botón */
            padding: 10px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            background-color: #007bff; /* Color de fondo */
            color: #fff; /* Color del texto */
            cursor: pointer; /* Cursor de puntero */
            font-size: 16px; /* Tamaño de fuente */
            text-align: center; /* Alinear texto al centro */
            display: block; /* Mostrar como bloque */
            margin-top: 10px; /* Margen superior */
        }

        /* Efectos de hover en el botón de envío */
        .register-form button[type="submit"]:hover {
            background-color: #0056b3; /* Color de fondo en hover */
        }

        /* Estilos del enlace de login */
        .register-form a#bl {
            display: inline-block; /* Mostrar como bloque en línea */
            width: 95%; /* Ancho del enlace */
            height: 20px; /* Altura del enlace */
            padding: 10px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            background-color: #007bff; /* Color de fondo */
            color: #fff; /* Color del texto */
            text-align: center; /* Alinear texto al centro */
            text-decoration: none; /* Sin subrayado */
            margin-top: 10px; /* Margen superior */
        }

        /* Efectos de hover en el enlace de login */
        .register-form a#bl:hover {
            background-color: #0056b3; /* Color de fondo en hover */
        }

        /* Estilos de error */
        .error {
            color: #e74c3c; /* Color del texto de error */
            font-size: 14px; /* Tamaño de fuente */
            margin-bottom: 20px; /* Margen inferior */
        }
    </style>
</head>

<body>
    <div class="register-form">
        <h2>No eres de por aqui? Registrate</h2>
        <?= session()->getFlashdata('success') ?>
        <form method="post" action="<?= site_url('registerUser') ?>">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Registrar</button>
        </form>
        <br>
        <a id="bl" href="<?= site_url('login') ?>">Ir a login</a>
    </div>
</body>
</html>