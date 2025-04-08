<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .recovery-form {
            width: 90%;
            max-width: 400px;
            padding: 40px 30px;
            background-color: #005f87;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid #004b6b;
            position: relative;
            overflow: hidden;
        }

        .recovery-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .recovery-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffcc00;
            font-size: 2rem;
            font-family: 'Baskervville', serif;
            position: relative;
        }

        .recovery-form h2::after {
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
            margin-bottom: 25px;
        }

        .recovery-form label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: 500;
        }

        .recovery-form input[type="email"] {
            width: 100%;
            padding: 15px 20px;
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .recovery-form input[type="email"]:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        .recovery-form button[type="submit"] {
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
        }

        .recovery-form button[type="submit"]::before {
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

        .recovery-form button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
        }

        .recovery-form button[type="submit"]:hover::before {
            width: 100%;
        }

        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .recovery-links {
            margin-top: 20px;
            text-align: center;
        }

        .recovery-links a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            display: inline-block;
            margin: 5px 0;
        }

        .recovery-links a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .recovery-form {
                padding: 30px 20px;
            }
            
            .recovery-form h2 {
                font-size: 1.8rem;
            }
            
            .recovery-form input[type="email"],
            .recovery-form button[type="submit"] {
                padding: 12px 15px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="recovery-form">
        <h2>Recuperar contraseña</h2>
        
        <?php if (session()->getFlashdata('message')): ?>
            <div class="error"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>
        
        <form action="<?= site_url('passwordreset/request') ?>" method="post">
            <div class="input-container">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>
        
        <div class="recovery-links">
            <a href="<?= site_url('login') ?>">Volver al inicio de sesión</a>
        </div>
    </div>
</body>
</html>