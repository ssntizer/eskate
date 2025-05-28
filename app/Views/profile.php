<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@500;700&display=swap');

/* Estilos generales */
body {
    background-color: #00719c; /* Fondo de la página */
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    color: #ffffff; /* Texto blanco */
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    padding-top: 70px; /* Espacio para el header fijo */
    min-height: 100vh; /* Asegura que el body ocupe al menos toda la altura de la vista */
    display: flex; /* Habilita Flexbox */
    flex-direction: column; /* Apila los elementos hijos verticalmente */
}

/* Header mejorado */
.header {
    background-color: #005f87; /* Color de fondo del encabezado */
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid #004b6b;
    position: fixed; /* Fija el header en la parte superior */
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000; /* Asegura que esté siempre sobre el contenido */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.header h1 {
    color: #ffffff;
    margin: 0;
    font-size: 1.8rem;
    font-family: "Quicksand", sans-serif;
    font-weight: bold;
    letter-spacing: 1px;
}

/* Contenedor flexible para nav y botón de perfil */
.header-right {
    display: flex;
    align-items: center; /* Alinea verticalmente los elementos */
    gap: 25px; /* Espacio entre nav-links y profile-button */
}

.nav-links {
    display: flex;
    gap: 25px;
}

.nav-links a {
    color: #ffffff;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    padding: 8px 15px;
    border-radius: 30px;
    position: relative;
    font-weight: 500;
}

.nav-links a:hover {
    color: #ffcc00;
    background-color: rgba(255, 204, 0, 0.1);
}

.nav-links a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: #ffcc00;
    transition: width 0.3s ease;
}

.nav-links a:hover::after {
    width: 100%;
}

/* Estilo para el nuevo botón de perfil */
.profile-button {
    display: flex; /* Permite centrar el icono */
    align-items: center;
    justify-content: center;
    background-color: #ffcc00; /* Color de fondo amarillo */
    color: #333; /* Color del icono oscuro */
    width: 40px; /* Ancho fijo para hacerlo redondo */
    height: 40px; /* Alto fijo para hacerlo redondo */
    border-radius: 50%; /* Lo hace redondo */
    text-decoration: none; /* Quita el subrayado */
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra sutil */
    font-size: 1.2rem; /* Tamaño del icono */
    flex-shrink: 0; /* Evita que se encoja en pantallas pequeñas */
}

.profile-button:hover {
    background-color: #ffb700; /* Amarillo más oscuro al pasar el ratón */
    color: #333;
    transform: scale(1.1); /* Efecto de escala */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

/* Estilos específicos para el Perfil */
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

/* Footer - Ajustado para ser sticky */
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

/* Media Queries */
@media (max-width: 992px) {
    /* Reglas si es necesario */
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        padding: 15px 0;
         gap: 10px; /* Espacio al apilar elementos del header */
    }

    .header h1 {
        margin-bottom: 15px;
        font-size: 1.8rem;
    }

     .header-right {
        flex-direction: column; /* Apila nav-links y profile-button */
        gap: 10px; /* Espacio entre elementos apilados */
        width: 100%; /* Ocupa todo el ancho */
        align-items: center; /* Centra los elementos apilados */
    }

    .nav-links {
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-links a {
        padding: 6px 12px;
        font-size: 1rem;
    }

    .profile-button {
        margin-top: 5px; /* Espacio adicional si está apilado */
    }

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
     .header-right {
         gap: 5px; /* Reduce el espacio si se apilan mucho */
     }
     .nav-links {
         gap: 10px; /* Reduce el espacio entre links */
     }
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

      .profile-button {
         width: 35px; /* Un poco más pequeño en pantallas muy pequeñas */
         height: 35px;
         font-size: 1rem;
     }
     
     .change-btn {
        top: 26px;
        height: 38px;
        width: 80px;
        font-size: 0.8rem;
    }
}
    </style>
</head>
<body>
<script src="https://kit.fontawesome.com/releases/v6.5.1/js/all.js" crossorigin="anonymous"></script>

<div class="header">
    <h1><a href="<?= site_url('/') ?>" style="text-decoration: none; color: inherit;">E-Skate</a></h1>
    <div class="header-right">
        <div class="nav-links">
                <a href="<?= site_url('/') ?>">Inicio</a> <a href="<?= site_url('list-skates') ?>">Mis Skates</a>
                <a href="<?= site_url('logout') ?>">Salir</a>
        </div>
    </div>
</div>

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
</div> <footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> E-Skate. Todos los derechos reservados.</p>
        <p><a href="#">Política de Privacidad</a> | <a href="#">Términos de Servicio</a></p>
    </div>
</footer>

<script>
    function enableField(fieldId) {
        const field = document.getElementById(fieldId);
        field.disabled = false;
        field.focus();
    }
</script>

<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) { // Verifica si el elemento existe
                 targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                // Si el enlace es a una sección de otra página (como en el header)
                // Redirige y luego intenta hacer scroll (puede requerir lógica adicional al cargar la página)
                // Nota: Esta lógica puede no ser perfecta para todas las situaciones.
                window.location.href = '<?= site_url('/') ?>' + targetId;
            }
        });
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

  // Los listeners y timeout relacionados con el botón de instalación PWA se pueden quitar
  // si no hay botón de instalación en esta página.
</script>
</body>
</html>