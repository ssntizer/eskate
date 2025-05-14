<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="manifest" href="/manifest.json">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style 
    contenteditable
    style="display: block";>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@500;700&display=swap');

/* Estilos generales - Copiados de tu código */
body {
    background-color: #00719c; /* Fondo de la página */
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    color: #ffffff; /* Texto blanco */
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    padding-top: 70px; /* Espacio para el header fijo */
}

/* Header mejorado - Copiado de tu código */
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
    margin-bottom: 20px; /* Espacio entre campos */
    border-radius: 8px;
    border: 2px solid #004b6b;
    background-color: rgba(255, 255, 255, 0.9); /* Fondo del input */
    transition: all 0.3s ease;
    font-size: 1rem;
    color: #333; /* Color del texto dentro del input */
}

.profile-container input[type="text"]:focus,
.profile-container input[type="email"]:focus,
.profile-container input[type="password"]:focus {
    outline: none;
    border-color: #ffcc00;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3); /* Sombra al enfocar */
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
}

.alert-danger-custom {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}


/* Footer mejorado - Copiado de tu código */
footer {
    background-color: #004b6b;
    color: #fff;
    padding: 40px 0 20px;
    text-align: center;
    position: relative;
    /* Añade espacio si el contenido es corto y el footer se pega mucho */
    margin-top: 50px;
}

footer p {
    margin: 0 0 15px;
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

/* Media Queries - Adaptadas del código principal */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        padding: 15px 0;
    }
    
    .header h1 {
        margin-bottom: 15px;
        font-size: 1.8rem;
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
        padding: 12px 30px; /* Ajusta el padding del botón */
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
     .profile-container {
        padding: 20px; /* Ajusta el padding */
    }

     .profile-container h2 {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>

<div class="header">
    <h1><a href="<?= site_url('/') ?>" style="text-decoration: none; color: inherit;">E-Skate</a></h1> 
    <div class="nav-links">
        <a href="<?= site_url('/') ?>#quienes-somos">¿Quiénes Somos?</a>
        <a href="<?= site_url('/') ?>#nuestros-productos">Nuestros Productos</a>
        <a href="<?= site_url('/') ?>#contactanos">Contáctanos</a>
        <?php if(session()->get('logged_in')): ?>
            <a href="<?= site_url('list-skates') ?>">Mis Skates</a>
            <a href="<?= site_url('logout') ?>">Salir</a>
        <?php else: ?>
             <a href="<?= site_url('login') ?>">Ingresar</a>
        <?php endif; ?>
    </div>
</div>

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

        <form action="<?= site_url('update-profile') ?>" method="post">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" value="<?= $user['username'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                 <input type="email" id="email" name="email" value="<?= $user['email'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" id="password" name="password" placeholder="Ingresa nueva contraseña">
                </div>

            <button type="submit">Actualizar Perfil</button>
        </form>
    </div>
</div>

<footer>
    <div class="container">
        <div class="social-links">
             <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        </div>
        <p>&copy; <?= date('Y') ?> E-Skate. Todos los derechos reservados.</p>
        <p><a href="#">Política de Privacidad</a> | <a href="#">Términos de Servicio</a></p>
    </div>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
    // Solo muestra el botón si está en la página principal o donde decidas
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