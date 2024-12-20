<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dirección</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');

        body {
            background-color: #00719c; /* Color de fondo */
            color: #ffffff; /* Color del texto */
            font-family: "Baskervville SC", serif; /* Fuente del texto */
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura completa de la ventana */
            margin: 0;
        }

        .register-form {
            width: 90%; /* Ancho del formulario adaptativo */
            max-width: 400px; /* Ancho máximo del formulario */
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

        .register-form input[type="number"],
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
            -webkit-appearance: none; /* Desactivar estilos predeterminados del navegador */
            -moz-appearance: none; /* Desactivar estilos predeterminados del navegador */
            appearance: none; /* Desactivar estilos predeterminados del navegador */
        }

        .register-form input[type="number"]:focus,
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

        .error,
        .success {
            font-size: 14px; /* Tamaño de fuente */
            margin-bottom: 20px; /* Margen inferior */
            text-align: left; /* Alinear texto a la izquierda */
        }

        .error {
            color: #e74c3c; /* Color del texto de error */
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
        <h2>Registrar Dirección</h2>
        <form id="addressForm" method="post" action="<?= site_url('direccion/guardar') ?>">
            <input type="text" name="calle" placeholder="Calle" value="<?= old('calle') ?>" required>
            <input type="text" name="provincia" placeholder="Numero" value="<?= old('numero') ?>" required>
            <input type="text" name="ciudad" placeholder="Ciudad" value="<?= old('ciudad') ?>" required>
            <input type="number" name="numero" placeholder="Provincia" value="<?= old('provincia') ?>" required>
            <button type="submit">Guardar Dirección</button>
        </form>
    </div>
</body>

</html>