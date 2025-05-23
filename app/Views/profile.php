<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <style>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');
       @import url('https://fonts.googleapis.com/css2?family=Baskervville+SC&display=swap'); /* Añadido Baskervville SC para el título */

        /* Ajuste para el área segura en dispositivos móviles */
        body {
            background-color: #00719c; /* Fondo de la página */
            color: #ffffff; /* Texto blanco */
            font-family: 'Montserrat', sans-serif;
            background-image: url('https://example.com/skate-pattern.png'), url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura ligera de asfalto */
            background-size: cover, auto;
            background-position: center;
            display: flex; /* Habilita Flexbox */
            flex-direction: column; /* Apila los elementos hijos verticalmente */
            min-height: 100vh; /* Asegura que el body ocupe al menos toda la altura de la vista */
            margin: 0;
            /* Añade padding superior dinámicamente para evitar el notch/barra de estado */
            padding-top: env(safe-area-inset-top);
            /* Para que el scroll se vea bien si hay padding */
            scroll-padding-top: env(safe-area-inset-top);
        }

        .header {
            background-color: #005f87; /* Color de fondo del encabezado */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004b6b;
            position: sticky; /* Cambiado de fixed a sticky para mejor comportamiento con el scroll */
            top: 0;
            left: 0;
            right: 0; /* Asegura que ocupe todo el ancho */
            z-index: 1001; /* Asegura que esté siempre sobre el contenido */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* Ajusta el padding superior del header si es fixed/sticky para no superponerse */
            padding-top: calc(15px + env(safe-area-inset-top));
            box-sizing: border-box; /* Asegura que el padding no añada ancho/alto total inesperado */
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 1.5rem; /* Ajustado para que el título no sea tan grande */
            font-family: "Baskervville SC", static; /* Usando Baskervville SC para el título */
            font-weight: bold; /* Mantener negrita si se desea */
            letter-spacing: 1px;
        }

        /* MEDIA QUERY para ajustar el título del header y su altura solo en pantallas pequeñas */
        @media (max-width: 767.98px) { /* Bootstrap's 'md' breakpoint */
            .header {
                /* Aumentamos el padding inferior del header para hacerlo un poco más alto */
                padding-bottom: 25px; /* Valor ajustado para que el título se vea mejor */
            }
            .header h1 {
                /* Ajustar el tamaño de fuente para que quepa mejor si es necesario */
                font-size: 1.4rem;
                /* Aseguramos que el título no quede bajo el notch en móviles, si es necesario */
                margin-top: env(safe-area-inset-top);
            }
        }

        /* Los estilos .header-right, .nav-links, .profile-button fueron eliminados o adaptados */

        /* Estilos específicos para el Perfil (Mantenidos de tu código original) */
        .profile-container {
            background-color: #005f87;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 80px auto; /* Espacio arriba y abajo, centrado */
            max-width: 700px; /* Ancho máximo similar al formulario de contacto */
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700); /* Gradiente en la parte superior */
        }

        .profile-container h2 {
            color: #ffcc00;
            margin-bottom: 30px;
            font-size: 2.2rem;
            text-align: center;
            position: relative; /* Para el pseudo-elemento after */
        }

        /* Línea bajo el título del perfil */
        .profile-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px; /* Ajusta según sea necesario */
            left: 50%;
            transform: translateX(-50%);
            width: 100px; /* Ancho de la línea */
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
        }

        .profile-container .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .profile-container .form-group label {
            color: #ffffff; /* Color de la etiqueta */
            font-weight: bold;
            margin-bottom: 5px;
            display: block; /* Para que la etiqueta esté en su propia línea */
        }

        .profile-container input[type="text"],
        .profile-container input[type="email"],
        .profile-container input[type="password"] {
            width: 100%;
            padding: 12px; /* Ajusta el padding */
            margin-bottom: 5px; /* Espacio entre campos */
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9); /* Fondo del input */
            transition: all 0.3s ease;
            font-size: 1rem;
            color: #333; /* Color del texto dentro del input */
            padding-right: 120px; /* Espacio para el botón */
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
            color: #666; /* Color del placeholder */
        }

        .profile-container input[type="text"]:focus,
        .profile-container input[type="email"]:focus,
        .profile-container input[type="password"]:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3); /* Sombra al enfocar */
        }

        /* Botón de Cambiar */
        .change-btn {
            position: absolute;
            right: 0;
            top: 30px;
            background-color: #ffcc00;
            color: #333;
            border: none;
            padding: 10px 15px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            height: 46px;
            width: 100px;
        }

        .change-btn:hover {
            background-color: #ffb700;
        }

        /* Botón de Actualizar - Usa los estilos del botón principal */
        .profile-container button[type="submit"] {
            background-color: #ffcc00;
            color: #333;
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            display: block;
            margin: 30px auto 0; /* Espacio arriba, centrado */
            font-weight: 600;
            cursor: pointer;
            position: relative; /* Para el efecto hover */
            overflow: hidden; /* Para ocultar el efecto hover */
            z-index: 1;
        }

        .profile-container button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700; /* Color de fondo del efecto hover */
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

        /* Mensajes de éxito/error (Bootstrap alert styles adapted) */
        .alert-custom {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .alert-success-custom {
            color: #004085;
            background-color: #cce5ff;
            border-color: #b8daff;
            color: #155724; /* Color para éxito */
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
            color: #f8d7da; /* Usar un color que contraste con el fondo */
            background-color: #721c24; /* Fondo para el texto de error */
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 5px;
            display: block; /* Cada error en su línea */
            font-size: 0.9rem;
        }

        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Footer - Ajustado para ser sticky (Mantenido de tu código original) */
        footer {
            background-color: #004b6b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            border-top: 3px solid #005f87;
            margin-top: auto; /* Esto empuja el footer hacia abajo en un flex container */
            flex-shrink: 0; /* Evita que el footer se encoja */
        }

        footer p {
            margin: 0;
            font-size: 1rem;
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

        /* Media Queries (Mantenidos de tu código original) */
        @media (max-width: 992px) {
            /* Reglas si es necesario */
        }

        @media (max-width: 768px) {
            .header {
                /* No flex-direction: column; aquí si queremos título a la izquierda y hamburguesa a la derecha */
                padding: 15px; /* Vuelve a padding horizontal normal */
                justify-content: space-between; /* Asegura espacio entre título y hamburguesa */
                flex-direction: row; /* Fuerza la dirección de fila */
            }

            .header h1 {
                margin-bottom: 0; /* No margin-bottom si es en fila */
                font-size: 1.5rem; /* Ajustado */
            }
            /* .header-right fue eliminado */
            /* .nav-links fue eliminado */
            /* .profile-button fue eliminado */

            .profile-container {
                padding: 30px; /* Ajusta el padding en pantallas pequeñas */
                margin: 50px auto; /* Ajusta el margen */
                max-width: 95%; /* Permite que ocupe más ancho en pantallas pequeñas */
            }

            .profile-container h2 {
                font-size: 1.8rem;
            }

            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 10px; /* Ajusta el padding de inputs */
                margin-bottom: 15px; /* Ajusta el margen */
            }

            .profile-container button[type="submit"] {
                padding: 12px 30px;
                font-size: 1rem;
            }

            .change-btn {
                top: 28px;
                height: 42px;
                width: 90px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            /* .header-right fue eliminado */
            /* .nav-links fue eliminado */
            .profile-container {
                padding: 20px; /* Ajusta el padding */
            }

            .profile-container h2 {
                font-size: 1.5rem;
            }

            .profile-container input[type="text"],
            .profile-container input[type="email"],
            .profile-container input[type="password"] {
                padding: 10px;
                margin-bottom: 10px;
            }

            .profile-container button[type="submit"] {
                padding: 10px 25px;
                font-size: 0.9rem;
            }

            /* .profile-button fue eliminado */

            .change-btn {
                top: 26px;
                height: 38px;
                width: 80px;
                font-size: 0.8rem;
            }
        }
        
        /* ESTILOS DEL MENÚ HAMBURGUESA PERSONALIZADO (Copiados de welcome.php) */
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
            position: fixed; /* Ojo: esto lo pondrá fijo en la ventana, no en el header si el header no es fijo */
            top: calc(15px + env(safe-area-inset-top)); /* Igualamos al padding-top del header */
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
            top: 0; /* Esto será ajustado por JavaScript */
            right: -100vw;
            width: min(75vw, 300px);
            height: 100vh; /* Esto también se ajustará dinámicamente */
            background-color: #005f87; /* Color de fondo azul oscuro */
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 999; /* Z-index más bajo que el header (1001) */
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            /* Padding superior específico para el contenido del overlay. */
            padding-top: calc(15px + env(safe-area-inset-top)); /* Alineado con el header o un poco más */
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
            padding-top: 5px; /* Ajustado para que los enlaces no estén pegados al top */
        }

        .mobile-nav-overlay .bottom-links {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        body.menu-active {
            overflow: hidden;
        }
    </style>
</head>
<body>
<script src="https://kit.fontawesome.com/releases/v6.5.1/js/all.js" crossorigin="anonymous"></script>

<div class="header" id="mainHeader">
    <h1>Mi Perfil</h1> <div class="menu-icon" id="menuIcon">
        <i class="material-icons">menu</i>
    </div>
</div>

<nav class="mobile-nav-overlay" id="mobileNavOverlay">
    <div class="top-links">
        <ul class="mobile-nav-list">
            <li><a href="<?= site_url('/') ?>"><i class="material-icons">home</i> Inicio</a></li>
            <li><a href="<?= site_url('profile') ?>"><i class="material-icons">person</i> Perfil</a></li>     
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
        const mainHeader = document.getElementById('mainHeader'); // Obtenemos el header
        const navItems = mobileNavOverlay.querySelectorAll('.mobile-nav-list a');

        // Función para ajustar la posición y altura del overlay
        function adjustOverlayPosition() {
            if (mainHeader && mobileNavOverlay) {
                const headerHeight = mainHeader.offsetHeight; // Obtiene la altura total del header
                mobileNavOverlay.style.top = `${headerHeight}px`; // Posiciona el overlay debajo del header
                mobileNavOverlay.style.height = `calc(100vh - ${headerHeight}px)`; // Ajusta la altura del overlay
            }
        }

        // Ejecutar al cargar y al redimensionar la ventana
        adjustOverlayPosition();
        window.addEventListener('resize', adjustOverlayPosition);

        if (menuIcon && mobileNavOverlay) {
            menuIcon.addEventListener('click', () => {
                const isOpen = mobileNavOverlay.classList.toggle('is-open');
                document.body.classList.toggle('menu-active');

                // Cambiar el ícono de hamburguesa a cruz y viceversa
                menuIcon.querySelector('i').textContent = isOpen ? 'close' : 'menu';
            });

            // Cerrar menú al hacer clic en un enlace del menú
            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    mobileNavOverlay.classList.remove('is-open');
                    document.body.classList.remove('menu-active');
                    menuIcon.querySelector('i').textContent = 'menu';
                });
            });

            // Cerrar menú al hacer clic fuera del menú
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
    // document.getElementById('installButton').style.display = 'inline-block'; // Este botón no está en esta vista, se puede quitar o adaptar
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