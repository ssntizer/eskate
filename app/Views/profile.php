<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@500;700&family=Baskervville&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Ajuste para el área segura en dispositivos móviles y estilos generales */
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            background-size: auto;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            /* padding-top ajustado por el header, env(safe-area-inset-top) será manejado por el header */
            scroll-padding-top: env(safe-area-inset-top);
            box-sizing: border-box; /* Asegura que padding no añada ancho/alto inesperado */
        }

        .header {
            background-color: #005f87;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: sticky; /* Sticky para que se quede arriba en la PWA */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* padding-top dinámico para manejar el notch */
            padding-top: calc(15px + env(safe-area-inset-top));
            box-sizing: border-box;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 1.8rem; /* Tamaño consistente con la otra versión */
            font-family: "Quicksand", sans-serif; /* Consistente con la otra versión */
            font-weight: bold;
            letter-spacing: 1px;
            text-align: left;
            flex-grow: 1;
        }

        .header h1 a {
            text-decoration: none;
            color: inherit;
        }

        /* Ocultar botones de navegación normales en la PWA ya que usamos el menú hamburguesa */
        .header-buttons {
            display: none;
        }

        /* Estilos específicos para el Perfil */
        .profile-container {
            background-color: #005f87;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            /* Ajustar margin-top para que no choque con el header fijo */
            margin: 40px auto; /* Usar 40px para desktops y ajustar en media query */
            max-width: 700px;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            flex-grow: 1; /* Permite que el contenedor crezca y empuje el footer */
            display: flex; /* Para centrar el contenido si es más pequeño */
            flex-direction: column;
            justify-content: center; /* Centrar verticalmente */
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
            font-family: 'Baskervville', serif; /* Consistente con la otra versión */
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
            display: flex; /* Usar flexbox para alinear */
            align-items: center; /* Alinear verticalmente los elementos */
            position: relative; /* Mantener para el posicionamiento absoluto del botón en móviles si es necesario */
            margin-bottom: 20px;
            flex-wrap: wrap; /* Permite que los elementos se envuelvan en la siguiente línea */
        }

        .profile-container .form-group label {
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 0; /* Eliminar el margen inferior para flexbox */
            display: block;
            flex-basis: 25%; /* Ancho de la etiqueta */
            text-align: left;
            margin-right: 10px; /* Espacio entre etiqueta e input */
            min-width: 90px; /* Asegura un ancho mínimo para la etiqueta */
        }

        /* Estilo para el nuevo contenedor del input y botón */
        .input-group-wrapper {
            display: flex; /* Convierte este contenedor en un flex container */
            align-items: center; /* Centra verticalmente el input y el botón */
            flex-grow: 1; /* Permite que el wrapper ocupe el espacio restante */
            position: relative; /* Necesario para posicionar el botón dentro de este wrapper */
        }

        .profile-container input[type="text"],
        .profile-container input[type="email"],
        .profile-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 0; /* Eliminar el margen inferior para flexbox */
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            color: #333;
            box-sizing: border-box;
            flex-grow: 1; /* Permitir que el input ocupe el espacio restante */
            padding-right: 100px; /* **Aumentado para dejar más espacio al botón** */
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
            position: absolute; /* **Cambiado a absoluto para posicionamiento dentro del wrapper** */
            right: 10px; /* **10px desde el borde derecho del .input-group-wrapper** */
            top: 50%; /* **Centra verticalmente el botón dentro del wrapper** */
            transform: translateY(-50%); /* **Ajuste fino para centrar verticalmente** */
            margin-left: 0; /* Eliminar margen izquierdo */
            background-color: #ffcc00;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 50px; /* Bordes redondeados consistentes */
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            height: auto;
            min-width: 90px; /* Añadido un ancho mínimo */
            max-width: 120px; /* Añadido un ancho máximo para controlar la expansión */
            font-size: 0.85rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
            overflow: hidden; /* Muy importante para el efecto ::before */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0; /* Evita que el botón se encoja */
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
            color: #333;
            transform: translateY(-2px); /* Mover hacia arriba sutilmente */
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

        /* Mensajes de alerta */
        .alert-custom {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 0.95rem;
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
            font-size: 0.9rem; /* Consistente con la otra versión */
            width: 100%; /* Asegura que ocupe el ancho completo debajo del campo */
            /* En flexbox, esto debería estar fuera del .form-group o en una nueva línea */
            flex-basis: 100%; /* Ocupa toda la línea en un contexto flex */
            order: 4; /* Lo coloca al final de los elementos flex */
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
            margin-top: auto; /* Empuja el footer hacia abajo */
            flex-shrink: 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
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
            /* Posicionamiento fijo para que siempre esté visible en la PWA */
            position: fixed;
            top: calc(15px + env(safe-area-inset-top)); /* Ajusta para el notch */
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
            top: 0; /* Se ajusta con JS */
            right: -100vw;
            width: min(75vw, 300px);
            height: 100vh; /* Se ajusta con JS */
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

        /* Media Queries para responsividad */
        @media (min-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            /* En pantallas grandes, el menú hamburguesa no es necesario si tienes botones de navegación normales */
            /* Sin embargo, la consigna es NO CAMBIAR el botón hamburguesa, así que se mantendrá visible */
            .menu-icon {
                display: flex; /* Aseguramos que siempre sea visible si no hay otros botones de navegación */
            }
            .profile-container {
                margin: 80px auto; /* Mantener margen para desktop */
            }
            /* En desktop, el botón vuelve a ser relativo dentro del wrapper */
            .profile-container .form-group .change-btn {
                position: relative;
                transform: none;
                margin-left: 10px;
                top: auto;
                right: auto;
            }
            /* Y el padding-right del input puede ser menor */
            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding-right: 12px; /* Valor normal para desktop */
            }
        }

        @media (max-width: 767.98px) {
            .header {
                padding-bottom: 15px;
            }
            .header h1 {
                font-size: 1.6rem;
            }
            .profile-container {
                padding: 30px;
                margin: 30px auto;
                max-width: 95%;
            }

            .profile-container h2 {
                font-size: 1.8rem;
            }

            .profile-container .form-group {
                flex-direction: column; /* Apila los elementos en móvil */
                align-items: flex-start; /* Alinea al inicio de la columna */
            }

            .profile-container .form-group label {
                width: 100%; /* Ocupa todo el ancho en móvil */
                margin-bottom: 5px; /* Espacio debajo de la etiqueta */
                margin-right: 0; /* Eliminar margen derecho */
                text-align: left;
            }

            /* Asegurar que el input-group-wrapper ocupe todo el ancho disponible */
            .input-group-wrapper {
                width: 100%;
                margin-bottom: 15px; /* Espacio debajo del input-group-wrapper apilado */
            }

            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                width: 100%; /* Ocupa todo el ancho disponible dentro del wrapper */
                padding: 10px;
                margin-bottom: 0; /* Eliminar el margen inferior, ya que el botón estará al lado */
                padding-right: 90px; /* Dejar espacio para el botón Cambiar */
            }

            /* Botón "Cambiar" en móvil - Posicionamiento absoluto dentro del nuevo wrapper */
            .profile-container .form-group .change-btn {
                position: absolute;
                right: 10px; /* 10px desde el borde derecho del .input-group-wrapper */
                top: 50%; /* Centra verticalmente el botón dentro del wrapper */
                transform: translateY(-50%); /* Ajuste fino para centrar verticalmente */
                margin-left: 0; /* Eliminar margen izquierdo */
                font-size: 0.8rem;
                padding: 6px 10px;
                height: auto;
                width: auto;
                min-width: unset; /* Eliminar min-width específico para móvil si no es necesario */
                max-width: unset; /* Eliminar max-width específico para móvil si no es necesario */
                border-radius: 50px; /* Asegura los bordes redondeados en móvil también */
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

        @media (max-width: 320px) {
            .header h1 {
                font-size: 1.2rem;
            }
            .menu-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
                right: 10px;
                top: calc(10px + env(safe-area-inset-top)); /* Ajuste fino */
            }
            .profile-container {
                padding: 15px;
                margin: 15px auto;
            }
            .profile-container h2 {
                font-size: 1.4rem;
            }
            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 6px;
                font-size: 0.8rem;
                padding-right: 70px;
            }
            .profile-container .form-group label {
                font-size: 0.9rem;
            }
            .profile-container .form-group .change-btn {
                font-size: 0.65rem;
                padding: 4px 6px;
                min-width: unset;
                max-width: unset;
            }
            .profile-container button[type="submit"] {
                padding: 6px 15px;
                font-size: 0.8rem;
            }
            .alert-custom, .text-danger-custom {
                font-size: 0.75rem;
                padding: 8px;
            }
            .text-danger-custom {
                font-size: 0.7rem;
            }
            footer p {
                font-size: 0.75rem;
            }
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

        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
            padding: 0 10px; /* Alineado con el resto */
        }
    </style>
</head>
<body>
<script src="https://kit.fontawesome.com/releases/v6.5.1/js/all.js" crossorigin="anonymous"></script>

<div class="header" id="mainHeader">
    <h1><a href="<?= site_url('/') ?>">E-Skate</a></h1>
    <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('/') ?>"><i class="material-icons">home</i> Inicio</a></li>
            <li><a href="<?= site_url('list-skates') ?>"><i class="material-icons">directions_bike</i> Mis Skates</a></li> <li><a href="<?= site_url('profile') ?>"><i class="material-icons">person</i> Perfil</a></li>
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
                        <label for="new_password">Nueva Contraseña:</label> <input type="password" id="new_password" name="new_password" placeholder="Ingresa tu nueva contraseña">
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['new_password'])): ?> <div class="text-danger-custom">
                                <?= session()->getFlashdata('errors')['new_password'] ?>
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
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['confirm_new_password'])): ?>
                            <div class="text-danger-custom">
                                <?= session()->getFlashdata('errors')['confirm_new_password'] ?>
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
                // Si el overlay está abierto y el clic no fue dentro del overlay ni en el ícono del menú
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

        if (password === '') {
            passwordValid = false;
            errorText += 'Debes ingresar una nueva contraseña. ';
        }

        if (confirmPassword === '') {
            passwordValid = false;
            errorText += 'Debes confirmar la nueva contraseña. ';
        }

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