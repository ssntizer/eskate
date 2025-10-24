<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - E-Skate</title>
    <link rel=" LETTERspacing" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@500;700&family=Baskervville&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Estilos generales del BODY para coincidir con la página de bienvenida */
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 70px; /* Espacio para el header fijo */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        .header {
            background-color: #005f87;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 1.8rem;
            font-family: "Quicksand", sans-serif;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header h1 a {
            text-decoration: none;
            color: inherit;
        }

        .header-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-buttons a {
            color: #333;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 20px;
            border-radius: 50px;
            background-color: #ffcc00;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: inline-block;
        }

        .header-buttons a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700;
            transition: width 0.3s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .header-buttons a:hover {
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 204, 0, 0.4);
            text-decoration: none;
        }

        .header-buttons a:hover::before {
            width: 100%;
        }

        .menu-icon {
            background-color: #ffcc00;
            color: #333;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            z-index: 1001;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 24px;
        }

        .menu-icon:hover {
            background-color: #ffb700;
            transform: scale(1.05);
        }

        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            right: -100vw;
            width: min(75vw, 300px);
            height: 100vh;
            background-color: #005f87;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 999;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            padding-top: 70px;
        }

        .mobile-nav-overlay.is-open {
            right: 0;
        }

        .mobile-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-list li {
            margin-bottom: 10px;
        }

        .mobile-nav-list a {
            display: block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
        }

        .mobile-nav-list a:hover {
            background-color: #00719c;
            color: #ffcc00;
        }

        .mobile-nav-list a i.material-icons {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .mobile-nav-overlay .top-links {
            margin-bottom: auto;
            padding-top: 5px;
        }

        .mobile-nav-overlay .bottom-links {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        body.menu-active {
            overflow: hidden;
        }

        .main-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-bottom: 60px;
        }

        .container {
            width: 100%;
            max-width: 700px;
            padding-left: 15px;
            padding-right: 15px;
        }

        .profile-container {
            background-color: #005f87;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 40px auto;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .profile-container h2 {
            color: #ffcc00;
            margin-bottom: 30px;
            font-size: 2.2rem;
            text-align: center;
            position: relative;
            font-family: 'Baskervville', serif;
        }

        .profile-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
        }

        .profile-container .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .profile-container .form-group label {
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            width: 100%;
            text-align: left;
        }

        /* Estilo para el nuevo contenedor del input y botón */
        .input-group-wrapper {
            width: 100%;
            position: relative;
        }

        .profile-container input[type="text"],
        .profile-container input[type="email"],
        .profile-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 0;
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            color: #333;
            box-sizing: border-box;
        }

        .profile-container .input-group-wrapper input[type="text"],
        .profile-container .input-group-wrapper input[type="email"],
        .profile-container .input-group-wrapper input[type="password"] {
            padding-right: 110px;
        }

        .profile-container input[type="text"]:disabled,
        .profile-container input[type="email"]:disabled,
        .profile-container input[type="password"]:disabled {
            background-color: rgba(255, 255, 255, 0.7);
            cursor: not-allowed;
        }

        .profile-container input[type="text"]::placeholder,
        .profile-container input[type="email"]::placeholder,
        .profile-container input[type="password"]::placeholder {
            color: #666;
        }

        .profile-container input[type="text"]:focus,
        .profile-container input[type="email"]:focus,
        .profile-container input[type="password"]:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        /* Botón de Cambiar - Ajustado para Flexbox y efecto hover */
        .profile-container .form-group .change-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #ffcc00;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 90px;
            max-width: 120px;
            font-size: 0.85rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .change-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700;
            transition: width 0.3s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .change-btn:hover {
            background-color: #ffb700;
            color: #333;
            transform: translateY(-50%) translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .change-btn:hover::before {
            width: 100%;
        }

        /* Botón de Actualizar */
        .profile-container button[type="submit"] {
            background-color: #ffcc00;
            color: #333;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            display: block;
            margin: 30px auto 0;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-decoration: none;
        }

        .profile-container button[type="submit"]::before {
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

        .profile-container button[type="submit"]:hover {
            color: #333;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
        }

        .profile-container button[type="submit"]:hover::before {
            width: 100%;
        }

        /* Mensajes de éxito/error */
        .alert-custom {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .alert-success-custom {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger-custom {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-warning-custom {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        /* Estilos para errores de validación individuales */
        .text-danger-custom {
            color: #f8d7da;
            background-color: #721c24;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 5px;
            display: block;
            font-size: 0.9rem;
            width: 100%;
        }

        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .password-section h4 {
            color: #ffcc00;
            text-align: center;
        }

        .password-section p {
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            background-color: #004b6b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            position: relative;
            width: 100%;
            margin-top: auto;
            flex-shrink: 0;
            z-index: 999;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        /* MEDIA QUERIES */
        @media (min-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            .header-buttons a {
                font-size: 1rem;
                padding: 10px 25px;
            }
            .menu-icon {
                display: none;
            }
            .header-buttons {
                display: flex;
            }
        }

        @media (max-width: 767.98px) {
            body {
                padding-top: 70px;
            }
            .header {
                flex-direction: row;
                justify-content: space-between;
                padding: 15px;
            }

            .header h1 {
                margin-bottom: 0;
                text-align: left;
                font-size: 1.6rem;
            }

            .header-buttons {
                display: none;
            }
            .menu-icon {
                display: flex;
            }

            .profile-container {
                padding: 30px;
                margin: 30px auto;
                max-width: 95%;
            }

            .profile-container h2 {
                font-size: 1.8rem;
            }

            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 10px;
            }

            .profile-container .input-group-wrapper input[type="text"],
            .profile-container .input-group-wrapper input[type="email"],
            .profile-container .input-group-wrapper input[type="password"] {
                padding-right: 90px;
            }

            .profile-container .form-group .change-btn {
                font-size: 0.8rem;
                padding: 6px 10px;
            }

            .profile-container button[type="submit"] {
                padding: 10px 25px;
                font-size: 1rem;
            }

            .alert-custom, .text-danger-custom {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.4rem;
            }
            .menu-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            .profile-container {
                padding: 20px;
                margin: 20px auto;
            }
            .profile-container h2 {
                font-size: 1.6rem;
            }
            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 8px;
                font-size: 0.9rem;
            }
            .profile-container .input-group-wrapper input[type="text"],
            .profile-container .input-group-wrapper input[type="email"],
            .profile-container .input-group-wrapper input[type="password"] {
                padding-right: 80px;
            }
            /* Ajuste para el botón Cambiar en móviles muy pequeños */
            .profile-container .form-group .change-btn {
                font-size: 0.75rem;
                padding: 5px 8px;
            }
            .profile-container button[type="submit"] {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
            .alert-custom, .text-danger-custom {
                font-size: 0.85rem;
                padding: 10px;
            }
            footer p {
                font-size: 0.8rem;
            }
        }

        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
            padding: 0 10px; /* Alineado con el resto */
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
    </style>
</head>
<body>
<script src="https://kit.fontawesome.com/releases/v6.5.1/js/all.js" crossorigin="anonymous"></script>

<div class="header">
    <h1><a href="<?= site_url('/') ?>">E-Skate</a></h1>
    <div class="header-buttons">
        <a href="javascript:history.back()">Volver atrás</a>
        <a href="<?= site_url('list-skates') ?>">Mis Skates</a>
        <a href="<?= site_url('profile') ?>">Perfil</a>
        <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
    </div>
    <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('list-skates') ?>"><i class="material-icons">directions_bike</i> Mis Skates</a></li>
            <li><a href="<?= site_url('profile') ?>"><i class="material-icons">person</i> Perfil</a></li>
            <li><a href="javascript:history.back()"><i class="material-icons">arrow_back</i> Volver atrás</a></li>
        </ul>
    </div>
    <div class="bottom-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('logout') ?>"><i class="material-icons">exit_to_app</i> Cerrar sesión</a></li>
        </ul>
    </div>
</nav>

<div class="main-content-wrapper">
    <div class="container">
        <div class="profile-container">
            <h2>Mi Perfil</h2>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success-custom" role="alert">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger-custom" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
             <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-warning-custom" role="alert">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>


            <form id="profileForm" action="<?= site_url('update-profile') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="username">Nombre de Usuario:</label>
                    <div class="input-group-wrapper"> <input type="text" id="username" name="username" value="<?= old('username', $user['username'] ?? '') ?>" disabled required>
                        <button type="button" class="change-btn" onclick="enableField('username')">Cambiar</button>
                    </div>
                     <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['username'])): ?>
                        <div class="text-danger-custom">
                            <?= session()->getFlashdata('errors')['username'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <div class="input-group-wrapper"> <input type="email" id="email" name="email" value="<?= old('email', $user['email'] ?? '') ?>" disabled required>
                     <button type="button" class="change-btn" onclick="enableField('email')">Cambiar</button>
                    </div>
                     <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                        <div class="text-danger-custom">
                            <?= session()->getFlashdata('errors')['email'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                 <div class="password-section">
                     <h4>Cambiar Contraseña o Email</h4>
                     <p>Para cambiar tus datos debes ingresar tu contraseña actual.</p>

                     <div class="form-group">
                        <label for="current_password">Contraseña Actual:</label>
                        <input type="password" id="current_password" name="current_password" placeholder="Ingresa tu contraseña actual">
                         <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['current_password'])): ?>
                            <div class="text-danger-custom">
                                <?= session()->getFlashdata('errors')['current_password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Nueva Contraseña:</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Ingresa tu nueva contraseña">
                         <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])): ?>
                            <div class="text-danger-custom">
                                <?= session()->getFlashdata('errors')['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="password-requirements">
                        <ul>
                            <li id="length">Al menos 8 caracteres</li>
                            <li id="uppercase">Al menos una letra mayúscula</li>
                            <li id="symbol">Al menos un símbolo (!@#$%^&*()_+-=[]{}|;':",./<>?)</li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_password">Confirmar Nueva Contraseña:</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Confirma tu nueva contraseña">
                         <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['confirm_password'])): ?>
                            <div class="text-danger-custom">
                                <?= session()->getFlashdata('errors')['confirm_password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="error-message" class="error"></div>
                 </div>

                 <button type="submit">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</div>
<footer>
    <p>&copy; 2024 E-skate - Diseñado para la acción - <a href="mailto:eskatevz@gmail.com">Contáctanos</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function enableField(fieldId) {
        const field = document.getElementById(fieldId);
        field.disabled = false;
        field.focus();
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuIcon = document.getElementById('menuIcon');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const mainHeader = document.querySelector('.header');
        const navItems = mobileNavOverlay.querySelectorAll('.mobile-nav-list a');

        function adjustOverlayPosition() {
            if (mainHeader && mobileNavOverlay) {
                const headerHeight = mainHeader.offsetHeight;
                mobileNavOverlay.style.top = `${headerHeight}px`;
                mobileNavOverlay.style.height = `calc(100vh - ${headerHeight}px)`;
            }
        }

        adjustOverlayPosition();
        window.addEventListener('resize', adjustOverlayPosition);

        if (menuIcon && mobileNavOverlay) {
            menuIcon.addEventListener('click', () => {
                const isOpen = mobileNavOverlay.classList.toggle('is-open');
                document.body.classList.toggle('menu-active');
                menuIcon.querySelector('i').textContent = isOpen ? 'close' : 'menu';
            });

            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    mobileNavOverlay.classList.remove('is-open');
                    document.body.classList.remove('menu-active');
                    menuIcon.querySelector('i').textContent = 'menu';
                });
            });

            document.body.addEventListener('click', (event) => {
                if (mobileNavOverlay.classList.contains('is-open') &&
                    !mobileNavOverlay.contains(event.target) &&
                    !menuIcon.contains(event.target)) {

                    mobileNavOverlay.classList.remove('is-open');
                    document.body.classList.remove('menu-active');
                    menuIcon.querySelector('i').textContent = 'menu';
                }
            });
        }
    });
</script>

<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                 targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                window.location.href = '<?= site_url('/') ?>' + targetId;
            }
        });
    });
</script>

<script>
    const passwordInput = document.getElementById('new_password');
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

    document.getElementById('profileForm').addEventListener('submit', function (event) {
        const password = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_new_password').value;
        const currentPassword = document.getElementById('current_password').value;
        const errorMessage = document.getElementById('error-message');

        if (password === '' && confirmPassword === '' && currentPassword === '') {
            errorMessage.style.display = 'none';
            return;
        }

        let passwordValid = true;
        let errorText = '';

        if (currentPassword === '') {
            passwordValid = false;
            errorText += 'Debes ingresar tu contraseña actual para cambiarla. ';
        }

        
    if(password !== ''){

        if (password.length < 8) {
            passwordValid = false;
            errorText += 'La nueva contraseña debe tener al menos 8 caracteres. ';
        }

        if (!/[A-Z]/.test(password)) {
            passwordValid = false;
            errorText += 'La nueva contraseña debe tener al menos una letra mayúscula. ';
        }

        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
            passwordValid = false;
            errorText += 'La nueva contraseña debe tener al menos un símbolo. ';
        }

        if (!passwordValid) {
            errorMessage.textContent = errorText;
            errorMessage.style.display = 'block';
            event.preventDefault();
            return;
        }
    }

        // Verificar coincidencia de contraseñas
        if (password !== confirmPassword) {
            errorMessage.textContent = 'Las nuevas contraseñas no coinciden.';
            errorMessage.style.display = 'block';
            event.preventDefault();
        } else {
            errorMessage.style.display = 'none';
        }
    });
</script>

</body>
</html>