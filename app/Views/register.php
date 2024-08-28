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
            background-color: #f2f2f2;
        }

        /* Estilos del formulario */
        .register-form {
            width: 400px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        /* Estilos de los campos de entrada */
        .register-form input[type="text"], .register-form input[type="email"], .register-form input[type="password"] {
            width: 90%;
            height: 40px;
            margin-bottom: 30px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        /* Estilos del botón de envío */
        .register-form button[type="submit"] {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }

        /* Efectos de hover en el botón de envío */
        .register-form button[type="submit"]:hover {
            background-color: #3e8e41;
        }

        /* Estilos del enlace de login */
        #bl {
            display: inline-block; /* Para ajustar el tamaño del enlace como un botón */
            width: 95%; /* Para que tenga el mismo ancho que el botón de registro */
            height: 20px; /* Altura del enlace igual a la del botón de registro */
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50; /* Mismo color de fondo que el botón de registro */
            color: #fff;
            text-align: center; /* Centrar el texto dentro del enlace */
            text-decoration: none; /* Eliminar el subrayado del enlace */
          
        }

        /* Efectos de hover en el enlace de login */
        #bl:hover {
            background-color: #3e8e41; /* Mismo color de fondo al pasar el ratón */
        }
    </style>
</head>

<body>
    <div class="register-form">
        <?= session()->getFlashdata('success') ?>
        <form method="post" action="<?= site_url('registerUser') ?>">
            <input type="text" name="username" placeholder="Username">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Register</button>
        </form>
        <br>
        <center><a id="bl" href="<?= site_url('login') ?>">Ir a login</a></center>
    </div>
</body>
</html>
