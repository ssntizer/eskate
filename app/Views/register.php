<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | E-Skate</title>
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

        .register-form {
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

        .register-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .register-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffcc00;
            font-size: 2rem;
            font-family: 'Baskervville', serif;
            position: relative;
            padding: 0 10px; /* Asegura que el título no toque los bordes */
        }

        .register-form h2::after {
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

        .register-form input[type="text"],
        .register-form input[type="email"], 
        .register-form input[type="password"] {
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

        .register-form input[type="text"]:focus,
        .register-form input[type="email"]:focus, 
        .register-form input[type="password"]:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        .button-container {
            padding: 0 10px; /* Mismo padding que los inputs */
            box-sizing: border-box;
        }

        .register-form button[type="submit"] {
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

        .register-form button[type="submit"]::before {
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

        .register-form button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
        }

        .register-form button[type="submit"]:hover::before {
            width: 100%;
        }

        .register-links {
            margin-top: 20px;
            text-align: center;
            padding: 0 10px; /* Mismo padding para consistencia */
        }

        .register-links a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            display: block;
            margin: 12px 0; /* Espaciado uniforme */
            padding: 5px 0; /* Pequeño padding para mejor tact */
        }

        .register-links a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        .error,
        .success {
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
            padding: 0 10px; /* Alineado con el resto */
        }

        .success {
            color: #2ecc71;
        }

        #password-requirements {
            text-align: left;
            margin-bottom: 20px;
            font-size: 0.9rem;
            padding: 0 10px; /* Alineado */
            color: #ffffff;
        }

        #password-requirements ul {
            padding-left: 20px;
            margin: 0;
        }

        #password-requirements li {
            color: #ff6b6b; /* Rojo por defecto */
        }

        #password-requirements li.valid {
            color: #2ecc71; /* Verde cuando válido */
        }

        #error-message {
            display: none;
        }

        @media (max-width: 576px) {
            .register-form {
                padding: 30px 20px;
            }
            
            .register-form h2 {
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
    <div class="register-form">
        <h2>Registro</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <form id="registrationForm" method="post" action="<?= site_url('registerUser') ?>">
            <div class="input-container">
                <input type="text" name="username" placeholder="Nombre de usuario" value="<?= old('username') ?>" required>
            </div>
            <div class="input-container">
                <input type="email" name="email" placeholder="Correo electrónico" value="<?= old('email') ?>" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
            </div>
            <div id="password-requirements">
                <ul>
                    <li id="length">Al menos 8 caracteres</li>
                    <li id="uppercase">Al menos una letra mayúscula</li>
                    <li id="symbol">Al menos un símbolo (!@#$%^&*()_+-=[]{}|;':",./<>?)</li>
                </ul>
            </div>
            <div class="input-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña" required>
            </div>
            <div id="error-message" class="error"></div>
            <div class="button-container">
                <button type="submit">Registrar</button>
            </div>
        </form>
        
        <div class="register-links">
            <a href="<?= site_url('login') ?>">Ir a login</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const lengthReq = document.getElementById('length');
        const uppercaseReq = document.getElementById('uppercase');
        const symbolReq = document.getElementById('symbol');

        passwordInput.addEventListener('input', function () {
            const password = passwordInput.value;

            // Validar longitud
            if (password.length >= 8) {
                lengthReq.classList.add('valid');
            } else {
                lengthReq.classList.remove('valid');
            }

            // Validar mayúscula
            if (/[A-Z]/.test(password)) {
                uppercaseReq.classList.add('valid');
            } else {
                uppercaseReq.classList.remove('valid');
            }

            // Validar símbolo
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                symbolReq.classList.add('valid');
            } else {
                symbolReq.classList.remove('valid');
            }
        });

        document.getElementById('registrationForm').addEventListener('submit', function (event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMessage = document.getElementById('error-message');

            // Verificar requisitos de contraseña
            let passwordValid = true;
            let errorText = '';

            if (password.length < 8) {
                passwordValid = false;
                errorText += 'La contraseña debe tener al menos 8 caracteres. ';
            }

            if (!/[A-Z]/.test(password)) {
                passwordValid = false;
                errorText += 'La contraseña debe tener al menos una letra mayúscula. ';
            }

            if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                passwordValid = false;
                errorText += 'La contraseña debe tener al menos un símbolo. ';
            }

            if (!passwordValid) {
                errorMessage.textContent = errorText;
                errorMessage.style.display = 'block';
                event.preventDefault();
                return;
            }

            // Verificar coincidencia de contraseñas
            if (password !== confirmPassword) {
                errorMessage.textContent = 'Las contraseñas no coinciden.';
                errorMessage.style.display = 'block';
                event.preventDefault();
            } else {
                errorMessage.style.display = 'none';
            }
        });
    </script>

</body>

</html>