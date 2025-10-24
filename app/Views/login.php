<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | E-Skate</title>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form {
            width: 90%;
            max-width: 400px;
            padding: 40px 30px; /* Ajuste de padding lateral */
            background-color: #005f87;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid #004b6b;
            position: relative;
            overflow: hidden;
        }

        .login-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffcc00;
            font-size: 2rem;
            font-family: 'Baskervville', serif;
            position: relative;
            padding: 0 10px; /* Asegura que el título no toque los bordes */
        }

        .login-form h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
        }

        .input-container {
            width: 100%;
            margin-bottom: 20px;
            padding: 0 10px; /* Padding simétrico para los inputs */
            box-sizing: border-box;
        }

        .login-form input[type="email"], 
        .login-form input[type="password"] {
            width: 100%;
            padding: 15px 20px; /* Más padding horizontal */
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            box-sizing: border-box; /* Asegura que el padding no afecte el ancho */
            display: block;
            margin: 0 auto; /* Centrado adicional */
        }

        .login-form input[type="email"]:focus, 
        .login-form input[type="password"]:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        .button-container {
            padding: 0 10px; /* Mismo padding que los inputs */
            box-sizing: border-box;
        }

        .login-form button[type="submit"] {
            background-color: #ffcc00;
            color: #333;
            padding: 15px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: block;
            margin: 0 auto; /* Centrado perfecto */
        }

        .login-form button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700;
            transition: width 0.4s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .login-form button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
        }

        .login-form button[type="submit"]:hover::before {
            width: 100%;
        }

        .login-links {
            margin-top: 20px;
            text-align: center;
            padding: 0 10px; /* Mismo padding para consistencia */
        }

        .login-links a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            display: block;
            margin: 12px 0; /* Espaciado uniforme */
            padding: 5px 0; /* Pequeño padding para mejor tact */
        }

        .login-links a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        /* Estilo para mensajes de error */
        .error {
            color: #ff6b6b; /* Rojo */
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
            padding: 0 10px; /* Alineado con el resto */
        }
        
        /* ⭐ NUEVO ESTILO PARA MENSAJES DE ÉXITO ⭐ */
        .success {
            color: #2ecc71; /* Verde */
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
            padding: 0 10px;
        }

        @media (max-width: 576px) {
            .login-form {
                padding: 30px 20px;
            }
            
            .login-form h2 {
                font-size: 1.8rem;
            }
            
            .input-container, 
            .button-container {
                padding: 0 5px; /* Padding ligeramente menor en móviles */
            }
        }
    </style>
</head>

<body>
    <div class="login-form">
        <h2>Iniciar Sesión</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= site_url('loginUser') ?>">
            <div class="input-container">
                <input type="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="button-container">
                <button type="submit">Iniciar Sesión</button>
            </div>
        </form>
        
        <div class="login-links">
            <a href="<?= site_url('register') ?>">Crear una cuenta</a>
            <a href="<?= site_url('forgot-password') ?>">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</body>
</html>