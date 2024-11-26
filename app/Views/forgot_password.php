<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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

        .login-form {
            width: 100%;
            max-width: 400px; /* Ancho máximo del formulario */
            padding: 40px; /* Espaciado interno */
            border-radius: 15px; /* Bordes redondeados */
            background: linear-gradient(145deg, #006f99, #008dc2); /* Degradado de fondo */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra */
            text-align: center; /* Alinear texto al centro */
        }

        .login-form h2 {
            margin-bottom: 20px; /* Margen inferior */
            font-family: "Baskervville SC", static; /* Fuente del título */
            font-size: 2rem; /* Tamaño de fuente */
        }

        .login-form input[type="email"] {
            width: 100%; /* Ancho completo */
            height: 50px; /* Altura del campo */
            margin-bottom: 20px; /* Margen inferior */
            padding: 10px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 10px; /* Bordes redondeados */
            font-size: 1.2rem; /* Tamaño de fuente */
            color: #333; /* Color del texto en los campos */
        }

        .login-form input[type="email"]:focus {
            border-color: #00e5ff; /* Color del borde al enfocar */
            outline: none; /* Sin contorno */
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.5); /* Sombra al enfocar */
        }

        .login-form button[type="submit"] {
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

        .login-form button[type="submit"]:hover {
            background-color: #e65c00; /* Color de fondo al pasar el mouse */
            transform: scale(1.05); /* Escala al pasar el mouse */
        }

        .login-form button[type="submit"]:active {
            transform: scale(0.98); /* Escala al hacer clic */
        }

        .error {
            color: #e74c3c; /* Color del texto de error */
            font-size: 14px; /* Tamaño de fuente */
            margin-bottom: 20px; /* Margen inferior */
        }

        .login-form p {
            color: #e74c3c; /* Color del mensaje */
        }

        @media (max-width: 768px) {
            body {
                height: auto; /* Ajustar la altura en dispositivos más pequeños */
                padding: 20px; /* Añadir padding */
            }

            .login-form {
                padding: 30px; /* Ajustar el padding del formulario */
            }

            .login-form h2 {
                font-size: 1.8rem; /* Ajustar el tamaño del título */
            }

            .login-form button[type="submit"] {
                height: 45px; /* Ajustar la altura del botón */
                font-size: 16px; /* Ajustar el tamaño de la fuente */
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 20px; /* Reducir el padding en pantallas más pequeñas */
            }

            .login-form h2 {
                font-size: 1.6rem; /* Reducir el tamaño del título */
            }

            .login-form button[type="submit"] {
                height: 40px; /* Reducir la altura del botón */
                font-size: 14px; /* Reducir el tamaño de la fuente */
            }

            .login-form input[type="email"] {
                height: 45px; /* Reducir la altura de los campos de texto */
                font-size: 1rem; /* Ajustar el tamaño de la fuente en los campos */
            }
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Recuperar contraseña</h2>
        <form action="<?= site_url('passwordreset/request') ?>" method="post">
            <div>
                <label for="email" style="color: #ffffff;">Correo electrónico:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>
        <?php if (session()->getFlashdata('message')): ?>
            <p class="error"><?= session()->getFlashdata('message') ?></p> <!-- Añadido estilo de error -->
        <?php endif; ?>
    </div>
</body>
</html>
