<!DOCTYPE html>
<html lang="es">
<head>
<link rel="manifest" href="/manifest.json">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Skate: Revoluciona tu Movimiento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

/* Estilos generales */
body {
    background-color: #00719c; /* Fondo de la página */
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    color: #ffffff; /* Texto blanco */
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    padding-top: 70px; /* Espacio para el header fijo */
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

/* Efecto de gradiente en el título principal */
.gradient-text {
    background: linear-gradient(90deg, #ffffff 0%, #ffffff 50%, #ffcc00 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    background-size: 200% auto;
    animation: gradient 3s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% center;
    }
    50% {
        background-position: 100% center;
    }
    100% {
        background-position: 0% center;
    }
}

/* Sección Hero mejorada */
.hero {
    background: linear-gradient(135deg, rgba(0, 113, 156, 0.5) 0%, rgba(0, 95, 135, 0.9) 100%), 
            url('https://www.wee-bot.com/cdn/shop/articles/617e33f8383a8184cc190092c765e1f1-722852.png?v=1734950608');
    background-size: cover;
    background-position: center;
    height: calc(100vh - 70px);
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(0,95,135,0.5) 100%);
}

.hero .container {
    position: relative;
    z-index: 1;
}

.hero h1 {
    font-size: 4.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
}

.hero p {
    font-size: 1.5rem;
    margin-bottom: 40px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
}

/* Botones mejorados */
.btn-main {
    background-color: #ffcc00;
    color: #333;
    padding: 15px 35px;
    font-size: 1.2rem;
    border-radius: 50px;
    border: none;
    transition: all 0.4s ease;
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3);
    font-weight: 600;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.btn-main::before {
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

.btn-main:hover {
    color: #333;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
}

.btn-main:hover::before {
    width: 100%;
}

#installButton {
    margin-top: 20px;
    display: none; /* Oculto inicialmente hasta que se active el evento PWA */
}

/* Sección ¿Quiénes Somos? */
.quienes-somos {
    padding: 80px 0;
    background-color: #005f87;
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    position: relative;
}

.quienes-somos::before {
    content: '';
    position: absolute;
    top: -20px;
    left: 0;
    width: 100%;
    height: 40px;
    background: linear-gradient(to bottom, rgba(0, 113, 156, 0.3), transparent);
}

.quienes-somos h2 {
    text-align: center;
    margin-bottom: 40px;
    color: #ffcc00;
    font-size: 2.8rem;
    position: relative;
    display: inline-block;
    left: 50%;
    transform: translateX(-50%);
}

.quienes-somos h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
}

.quienes-somos p {
    text-align: center;
    font-size: 1.2rem;
    margin: 0 auto 20px;
    color: white;
    max-width: 800px;
    line-height: 1.8;
}

/* Sección de Modelos mejorada */
.models {
    padding: 80px 0;
    background-color: #00719c;
    position: relative;
}

.models::before {
    content: '';
    position: absolute;
    top: -20px;
    left: 0;
    width: 100%;
    height: 40px;
    background: linear-gradient(to bottom, rgba(0, 95, 135, 0.3), transparent);
}

.models h2 {
    text-align: center;
    margin-bottom: 50px;
    color: #ffcc00;
    font-size: 2.8rem;
    position: relative;
}

.models h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
}

.feature {
    text-align: center;
    margin-bottom: 30px;
    padding: 25px;
    border-radius: 15px;
    transition: all 0.3s ease;
    background-color: rgba(0, 75, 107, 0.3);
}

.feature:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    background-color: rgba(0, 75, 107, 0.5);
}

.feature h4 {
    margin: 20px 0 10px;
    color: #ffcc00;
    font-size: 1.5rem;
}

.feature p {
    color: #ffffff;
    margin-bottom: 20px;
}

.feature img {
    width: 100%;
    height: auto;
    max-height: 250px;
    object-fit: cover;
    border: 3px solid #004b6b;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.feature:hover img {
    transform: scale(1.05);
    border-color: #ffcc00;
}

/* Formulario de contacto mejorado */
.contact-form {
    background-color: #005f87;
    padding: 60px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    margin: 80px auto;
    max-width: 800px;
    position: relative;
    overflow: hidden;
}

.contact-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #ffcc00, #ffb700);
}

.contact-form h3 {
    color: #ffcc00;
    margin-bottom: 30px;
    font-size: 2.2rem;
    text-align: center;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 8px;
    border: 2px solid #004b6b;
    background-color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
    font-size: 1rem;
}

.contact-form input:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: #ffcc00;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
}

.contact-form button {
    background-color: #ffcc00;
    color: #333;
    padding: 15px 40px;
    font-size: 1.1rem;
    border-radius: 50px;
    border: none;
    transition: all 0.4s ease;
    display: block;
    margin: 0 auto;
    font-weight: 600;
    cursor: pointer;
}

.contact-form button:hover {
    background-color: #ffb700;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
}

/* Footer mejorado */
footer {
    background-color: #004b6b;
    color: #fff;
    padding: 40px 0 20px;
    text-align: center;
    position: relative;
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

/* Media Queries */
@media (max-width: 992px) {
    .hero h1 {
        font-size: 3.5rem;
    }
    
    .models .feature {
        margin-bottom: 40px;
    }
}

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
    
    .hero h1 {
        font-size: 2.8rem;
    }
    
    .hero p {
        font-size: 1.2rem;
        max-width: 90%;
    }
    
    .btn-main {
        padding: 12px 25px;
        font-size: 1rem;
    }
    
    .quienes-somos h2,
    .models h2 {
        font-size: 2.2rem;
    }
    
    .quienes-somos p {
        font-size: 1.1rem;
        max-width: 90%;
    }
    
    .contact-form {
        padding: 40px;
    }
}

@media (max-width: 576px) {
    .hero h1 {
        font-size: 2.2rem;
    }
    
    .hero p {
        font-size: 1rem;
    }
    
    .quienes-somos h2,
    .models h2 {
        font-size: 1.8rem;
    }
    
    .contact-form {
        padding: 30px 20px;
    }
    
    .contact-form input,
    .contact-form textarea {
        padding: 12px;
    }
    
    .feature {
        padding: 20px 15px;
    }
}
    </style>
</head>
<body>

<div class="header">
    <h1>E-Skate</h1>
    <div class="nav-links">
        <a href="#quienes-somos">¿Quiénes Somos?</a>
        <a href="#nuestros-productos">Nuestros Productos</a>
        <a href="#contactanos">Contáctanos</a>
        <a href="<?= site_url('login') ?>">Ingresar</a>
    </div>
</div>

<!-- Sección Hero -->
<section class="hero">
    <div class="container">
        <h1 class="gradient-text">Revoluciona tu Movimiento</h1>
        <p>Explora el futuro del transporte con nuestras innovadoras e-skates.</p>
        <a href="<?= site_url('login') ?>" class="btn btn-main">Inicia Sesión para Monitorear tu Skate</a>
        <button id="installButton" class="btn-main">Instalar PWA</button>
    </div>
</section>

<!-- Sección ¿Quiénes Somos? -->
<section id="quienes-somos" class="quienes-somos">
    <div class="container">
        <h2>¿Quiénes Somos?</h2>
        <p>En E-Skate, somos una empresa dedicada a desarrollar tu transporte del futuro. Creamos e-skates, patinetas eléctricas recién traídas del futuro, que transforman la manera en que te desplazas.</p>
        <p>Con nuestra tecnología avanzada y diseño innovador, buscamos ofrecerte la mejor experiencia de movilidad, combinando velocidad, durabilidad y conectividad inteligente, pero sobre todo proporcionarte un medio de transporte muy seguro</p>
        <p>Únete a la revolución del transporte y descubre cómo podemos hacer tu vida más fácil y emocionante con nuestros productos de última generación.</p>
    </div>
</section>

<!-- Sección de Modelos -->
<section id="nuestros-productos" class="models">
    <div class="container">
        <h2>Nuestros Modelos</h2>
        <div class="row">
            <div class="col-md-4 feature">
                <img src="https://imgs.search.brave.com/tps24H47-2oaLseYhRphCnOSszeFXtoK-3EaI9JezrA/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9za2F0/ZXNlbGVjdHJpY29z/LmNvbS93cC1jb250/ZW50L3VwbG9hZHMv/MjAyMS8wNi9tZWVw/by1taW5pMi1zY2Fs/ZWQuanBlZw" alt="Modelo 1">
                <h4>Modelo E-Skate 1</h4>
                <p>Precio: $299</p>
                <a href="<?= base_url('/index.php/skate/detail/1') ?>" class="btn btn-main">Comprar</a>
            </div>
            <div class="col-md-4 feature">
                <img src="https://imgs.search.brave.com/qH8RsQ019QLQkGLFWZExzsnL4kvsrQ_GwfP-ckTx5pI/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTF1a3dQK3F5b1Mu/anBn" alt="Modelo 2">
                <h4>Modelo E-Skate 2</h4>
                <p>Precio: $599</p>
                <a href="<?= base_url('/index.php/skate/detail/2') ?>" class="btn btn-main">Comprar</a>
            </div>
            <div class="col-md-4 feature">
                <img src="https://imgs.search.brave.com/4hfX1Aw6h9uwaa7HX6i2vtgTdUT3mvVz1GoT5ojtQQE/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NDFNMnd5YTMzMEwu/anBn" alt="Modelo 3">
                <h4>Modelo E-Skate 3</h4>
                <p>Precio: $699</p>
                <a href="<?= base_url('/index.php/skate/detail/3') ?>" class="btn btn-main">Comprar</a>
            </div>
        </div>
    </div>
</section>

<!-- Formulario de contacto -->
<section id="contactanos" class="contact-form container">
    <h3>Contáctanos</h3>
    <form action="<?= site_url('enviarmail') ?>" method="post">
        <div class="form-group">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
        </div>
        <div class="form-group">
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
        </div>
        <div class="form-group">
            <input type="text" name="telefono" placeholder="Teléfono" required>
        </div>
        <div class="form-group">
            <textarea name="mensaje" rows="5" placeholder="Mensaje" required></textarea>
        </div>
        <button type="submit">Enviar</button>
    </form>
</section>

<footer>
        <p>&copy; 2024 E-Skate. Todos los derechos reservados.</p>
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

            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
<script>
  let installEvent;
  let installPopup;

  window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    installEvent = event;
    document.getElementById('installButton').style.display = 'inline-block';
  });

  window.addEventListener("message", (event) => {
    if (event.data === "cerrarPestana") {
      if (installPopup) {
        installPopup.close();
      }
    }
  });

  document.getElementById('installButton').addEventListener('click', () => {
    const screenWidth = window.screen.width;
    const screenHeight = window.screen.height;
    const popupWidth = 600;
    const popupHeight = 400;
    const leftPosition = (screenWidth - popupWidth) / 2;
    const topPosition = (screenHeight - popupHeight) / 2;

    installPopup = window.open(
      "https://eskate-prueba-erie.onrender.com/index.php/instalarpwa",
      "installPopup",
      `width=${popupWidth},height=${popupHeight},top=${topPosition},left=${leftPosition}`
    );
  });

  setTimeout(() => {
    if (!installEvent) {
      document.getElementById('installButton').style.display = 'inline-block';
    }
  }, 3000);
</script>
</body>
</html>