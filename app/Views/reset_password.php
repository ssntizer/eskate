<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: "Baskervville SC", static;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-password-form {
            width: 400px; /* Ancho del formulario igual al de registro */
            padding: 40px; /* Espaciado interno */
            border-radius: 15px; /* Bordes redondeados */
            background: linear-gradient(145deg, #006f99, #008dc2); /* Degradado de fondo */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra */
            text-align: center; /* Alinear texto al centro */
        }

        .reset-password-form h2 {
            margin-bottom: 20px; /* Margen inferior */
            font-family: "Baskervville SC", static; /* Fuente del título */
            font-size: 2rem; /* Tamaño de fuente */
        }

        .reset-password-form input[type="text"], 
        .reset-password-form input[type="password"] {
            width: 100%; /* Ancho completo */
            height: 50px; /* Altura del campo */
            margin-bottom: 20px; /* Margen inferior */
            padding: 10px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 10px; /* Bordes redondeados */
            font-size: 1.2rem; /* Tamaño de fuente */
            color: #333; /* Color del texto en los campos */
        }

        .reset-password-form input[type="text"]:focus, 
        .reset-password-form input[type="password"]:focus {
            border-color: #00e5ff; /* Color del borde al enfocar */
            outline: none; /* Sin contorno */
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.5); /* Sombra al enfocar */
        }

        .reset-password-form button[type="submit"] {
            width: 100%; /* Ancho completo */
            height: 50px; /* Altura del botón */
            border: none; /* Sin borde */
            border-radius: 10px; /* Bordes redondeados */
            background-color: #ff6600; /* Color de fondo */
            color: white; /* Color del texto */
            font-size: 18px; /* Tamaño de fuente */
            font-weight: 600; /* Peso de fuente */
            cursor: pointer; /* Cursor de puntero */
            transition: background-color 0.3s ease, transform 0.2s; /* Transiciones suaves */
        }

        .reset-password-form button[type="submit"]:hover {
            background-color: #e65c00; /* Color de fondo al pasar el mouse */
            transform: scale(1.05); /* Escala al pasar el mouse */
        }

        .reset-password-form button[type="submit"]:active {
            transform: scale(0.98); /* Escala al hacer clic */
        }

        .error {
            color: #e74c3c; /* Color del texto de error */
            font-size: 14px; /* Tamaño de fuente */
            margin-bottom: 20px; /* Margen inferior */
        }

        .reset-password-form a#bl {
            display: block; /* Mostrar como bloque */
            margin-top: 15px; /* Margen superior */
            text-decoration: none; /* Sin subrayado */
            font-size: 16px; /* Tamaño de fuente */
            font-weight: 600; /* Peso de fuente */
            color: #ffffff; /* Color del texto */
            transition: color 0.3s ease; /* Transición suave del color */
        }

        .reset-password-form a#bl:hover {
            color: #00e5ff; /* Color al pasar el mouse */
        }
    </style>
</head>

<body>
    <div class="reset-password-form">
        <h2>Restablecer Contraseña</h2>
        <?= session()->getFlashdata('error') ?>
        <form method="post" action="<?= site_url('passwordreset/update') ?>">
            <input type="text" name="token" placeholder="Ingresa tu token" required>
            <input type="password" name="new_password" placeholder="Nueva contraseña" required>
            <button type="submit">Restablecer contraseña</button>
        </form>
        <a id="bl" href="<?= site_url('login') ?>">Volver a iniciar sesión</a>
    </div>
</body>
</html>