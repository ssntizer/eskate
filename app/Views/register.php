<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');

        body {
            background-color: #00719c; /* Color de fondo */
            color: #ffffff; /* Color del texto */
            font-family: "Baskervville SC", serif;/* Fuente del texto */
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura completa de la ventana */
            margin: 0;
        }

        .register-form {
            width: 400px; /* Ancho del formulario */
            padding: 40px; /* Espaciado interno */
            border-radius: 15px; /* Bordes redondeados */
            background: linear-gradient(145deg, #006f99, #008dc2); /* Degradado de fondo */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra */
            text-align: center; /* Alinear texto al centro */
            color: #fff; /* Color del texto */
        }

        .register-form h2 {
            margin-bottom: 20px; /* Margen inferior */
            font-family: "Baskervville SC", static; /* Fuente del título */
            font-size: 2rem; /* Tamaño de fuente */
        }

        .register-form input[type="text"], 
        .register-form input[type="email"], 
        .register-form input[type="password"] {
            width: 100%; /* Ancho completo */
            height: 50px; /* Altura del campo */
            margin-bottom: 20px; /* Margen inferior */
            padding: 10px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 10px; /* Bordes redondeados */
            font-size: 1.2rem; /* Tamaño de fuente */
            color: #333; /* Color del texto en los campos */
        }

        .register-form input[type="text"]:focus, 
        .register-form input[type="email"]:focus, 
        .register-form input[type="password"]:focus {
            border-color: #00e5ff; /* Color del borde al enfocar */
            outline: none; /* Sin contorno */
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.5); /* Sombra al enfocar */
        }

        .register-form button[type="submit"] {
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

        .register-form button[type="submit"]:hover {
            background-color: #e65c00; /* Color de fondo al pasar el mouse */
            transform: scale(1.05); /* Escala al pasar el mouse */
        }

        .register-form button[type="submit"]:active {
            transform: scale(0.98); /* Escala al hacer clic */
        }

        .error, .success {
            color: #e74c3c; /* Color del texto de error */
            font-size: 14px; /* Tamaño de fuente */
            margin-bottom: 20px; /* Margen inferior */
            text-align: left; /* Alinear texto a la izquierda */
        }

        .success {
            color: #2ecc71; /* Color del texto de éxito */
        }

        .register-form a#bl {
            display: block; /* Mostrar como bloque */
            margin-top: 15px; /* Margen superior */
            text-decoration: none; /* Sin subrayado */
            font-size: 16px; /* Tamaño de fuente */
            font-weight: 600; /* Peso de fuente */
            color: #fff; /* Color del texto */
            transition: color 0.3s ease; /* Transición suave del color */
        }

        .register-form a#bl:hover {
            color: #00e5ff; /* Color al pasar el mouse */
        }
    </style>
</head>

<body>
    <div class="register-form">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <h2>Registro</h2>
        <form id="registrationForm" method="post" action="<?= site_url('registerUser') ?>">
            <input type="text" name="username" placeholder="Nombre de usuario" value="<?= old('username') ?>" required>
            <input type="email" name="email" placeholder="Correo electrónico" value="<?= old('email') ?>" required>
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña" required>
            <div id="error-message" class="error" style="display:none;"></div>
            <button type="submit">Registrar</button>
        </form>
        <a id="bl" href="<?= site_url('login') ?>">Ir a login</a>
    </div>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMessage = document.getElementById('error-message');
            
            if (password !== confirmPassword) {
                errorMessage.textContent = 'Las contraseñas no coinciden.'; // Mensaje de error
                errorMessage.style.display = 'block'; // Mostrar mensaje de error
                event.preventDefault(); // Previene el envío del formulario
            } else {
                errorMessage.style.display = 'none'; // Ocultar mensaje de error
            }
        });
    </script>

</body>
</html>