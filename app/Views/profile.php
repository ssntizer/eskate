<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');
       @import url('https://fonts.googleapis.com/css2?family=Baskervville+SC&display=swap');

        /* Ajuste para el área segura en dispositivos móviles */
        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Se eliminó la imagen de skate, ya que no estaba definida y se duplicaba con el pattern */
            background-size: auto; /* Ajuste para la textura */
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding-top: env(safe-area-inset-top);
            scroll-padding-top: env(safe-area-inset-top);
        }

        .header {
            background-color: #005f87;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding-top: calc(15px + env(safe-area-inset-top));
            box-sizing: border-box;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 1.5rem;
            font-family: "Baskervville SC", static;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: left; /* Asegura que el título siempre esté a la izquierda */
            flex-grow: 1; /* Permite que el título ocupe el espacio disponible */
        }

        @media (max-width: 767.98px) {
            .header {
                padding-bottom: 15px; /* Restablece el padding, el padding-top ya incluye el notch */
            }
            .header h1 {
                font-size: 1.3rem; /* Ligeramente más pequeño en móviles */
            }
        }

        /* Estilos específicos para el Perfil */
        .profile-container {
            background-color: #005f87;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 80px auto;
            max-width: 700px;
            position: relative;
            overflow: hidden;
            box-sizing: border-box; /* Asegura que el padding no añada ancho/alto total inesperado */
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
            margin-bottom: 20px;
            position: relative;
        }

        .profile-container .form-group label {
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .profile-container input[type="text"],
        .profile-container input[type="email"],
        .profile-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            color: #333;
            /* Ajustado para el botón cambiar en móviles */
            padding-right: 120px; /* Suficiente espacio para el botón Cambiar */
            box-sizing: border-box; /* Crucial para que el padding no cause desbordamiento */
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

        /* Botón de Cambiar */
        .change-btn {
            position: absolute;
            right: 0;
            top: 50%; /* Posición relativa al input */
            transform: translateY(-50%); /* Centrar verticalmente */
            background-color: #ffcc00;
            color: #333;
            border: none;
            padding: 10px 15px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            height: 100%; /* Ocupa toda la altura del input */
            width: 110px; /* Ancho un poco más pequeño para dar espacio */
            box-sizing: border-box;
            line-height: 1; /* Asegura el centrado vertical del texto */
        }

        .change-btn:hover {
            background-color: #ffb700;
        }

        /* Botón de Actualizar */
        .profile-container button[type="submit"] {
            background-color: #ffcc00;
            color: #333;
            padding: 15px 40px;
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

        /* Mensajes de alerta */
        .alert-custom {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 0.95rem; /* Ajuste de tamaño para móviles */
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

        /* Errores de validación */
        .text-danger-custom {
            color: #f8d7da;
            background-color: #721c24;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 5px;
            display: block;
            font-size: 0.85rem; /* Ligeramente más pequeño */
        }

        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Footer */
        footer {
            background-color: #004b6b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            border-top: 3px solid #005f87;
            margin-top: auto;
            flex-shrink: 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem; /* Un poco más pequeño en el footer */
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        footer a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            font-size: 1.5rem;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #ffcc00;
            transform: translateY(-3px);
        }

        /* ESTILOS DEL MENÚ HAMBURGUESA PERSONALIZADO */
        .menu-icon {
            background-color: #005f87;
            color: white;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            z-index: 1002;
            position: fixed;
            top: calc(15px + env(safe-area-inset-top));
            right: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .menu-icon:hover {
            background-color: #004b6b;
            transform: scale(1.05);
        }

        .menu-icon i {
            font-size: 28px;
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
            padding-top: calc(15px + env(safe-area-inset-top));
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

        /* Nuevas Media Queries para asegurar la responsividad en pantallas muy pequeñas */
        @media (max-width: 420px) {
            .header h1 {
                font-size: 1.2rem; /* Título aún más pequeño para evitar desbordamiento */
            }

            .menu-icon {
                width: 40px;
                height: 40px;
            }

            .menu-icon i {
                font-size: 24px;
            }

            .profile-container {
                padding: 15px; /* Reducir aún más el padding */
                margin: 40px auto; /* Reducir margen */
            }

            .profile-container h2 {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }

            .profile-container .form-group {
                margin-bottom: 15px;
            }

            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 10px;
                font-size: 0.9rem;
                padding-right: 90px; /* Reducir espacio para el botón Cambiar */
            }

            .change-btn {
                width: 80px; /* Ancho más pequeño para el botón */
                padding: 8px 10px;
                font-size: 0.75rem;
            }

            .profile-container button[type="submit"] {
                padding: 10px 25px;
                font-size: 0.9rem;
            }

            .alert-custom {
                font-size: 0.85rem;
                padding: 10px;
            }

            .text-danger-custom {
                font-size: 0.75rem;
                padding: 3px 8px;
            }
        }

        @media (max-width: 320px) {
            .header h1 {
                font-size: 1.1rem;
            }
            .menu-icon {
                width: 36px;
                height: 36px;
                right: 10px; /* Ajustar posición */
            }
            .menu-icon i {
                font-size: 20px;
            }
            .profile-container {
                padding: 10px;
                margin: 30px auto;
            }
            .profile-container h2 {
                font-size: 1.6rem;
            }
            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 8px;
                font-size: 0.85rem;
                padding-right: 75px;
            }
            .change-btn {
                width: 65px;
                padding: 6px 8px;
                font-size: 0.7rem;
            }
            .profile-container button[type="submit"] {
                padding: 8px 20px;
                font-size: 0.8rem;
            }
            .alert-custom {
                font-size: 0.8rem;
            }
            .text-danger-custom {
                font-size: 0.7rem;
            }
            footer p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
<script src="https://kit.fontawesome.com/releases/v6.5.1/js/all.js" crossorigin="anonymous"></script>

<div class="header" id="mainHeader">
    <h1>Mi Perfil</h1>
    <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('/') ?>"><i class="material-icons">home</i> Inicio</a></li>
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


            <form action="<?= site_url('update-profile') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="username">Nombre de Usuario:</label>
                    <input type="text" id="username" name="username" value="<?= old('username', $user['username'] ?? '') ?>" disabled required>
                    <button type="button" class="change-btn" onclick="enableField('username')">Cambiar</button>
                     <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['username'])): ?>
                        <div class="text-danger-custom">
                            <?= session()->getFlashdata('errors')['username'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                     <input type="email" id="email" name="email" value="<?= old('email', $user['email'] ?? '') ?>" disabled required>
                     <button type="button" class="change-btn" onclick="enableField('email')">Cambiar</button>
                     <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                        <div class="text-danger-custom">
                            <?= session()->getFlashdata('errors')['email'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                 <div class="password-section">
                     <h4>Cambiar Contraseña</h4>
                     <p>Para cambiar tu contraseña, debes ingresar tu contraseña actual.</p>

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
                        <label for="password">Nueva Contraseña:</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Ingresa tu nueva contraseña">
                         <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])): ?>
                            <div class="text-danger-custom">
                                <?= session()->getFlashdata('errors')['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                 </div>

                 <button type="submit">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> E-Skate. Todos los derechos reservados.</p>
        <p><a href="#">Política de Privacidad</a> | <a href="#">Términos de Servicio</a></p>
    </div>
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
        const mainHeader = document.getElementById('mainHeader');
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
  let installEvent;
  let installPopup;

  window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    installEvent = event;
  });

  window.addEventListener("message", (event) => {
    if (event.data === "cerrarPestana") {
      if (installPopup) {
        installPopup.close();
      }
    }
  });
</script>
</body>
</html>