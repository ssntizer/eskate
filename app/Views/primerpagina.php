<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Skate: Revoluciona tu Movimiento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
}

/* Header */
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
}

.header h1 {
    color: #ffcc00;
    margin: 0;
    font-size: 1.5rem;
}

.header a {
    color: #ffcc00;
    text-decoration: none;
    margin-left: 20px;
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

.header a:hover {
    color: #ffb700;
}

/* Sección Hero */
.hero {
    background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://example.com/hero-image.jpg'); /* Imagen con superposición */
    background-size: cover;
    background-position: center;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-align: center;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); /* Sombra */
}

.hero h1 {
    font-size: 4rem;
    font-weight: 700;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); /* Sombra en el texto */
}

.hero p {
    font-size: 1.5rem;
    margin-bottom: 30px;
}

.btn-main {
    background-color: #ffcc00;
    color: #333;
    padding: 15px 30px;
    font-size: 1.2rem;
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3); /* Sombra suave */
}

.btn-main:hover {
    background-color: #ffb700;
    transform: scale(1.05); /* Efecto de hover dinámico */
}

/* Sección de características (Features) */
.features {
    padding: 50px 0;
    background-color: #00719c; /* Mismo color que el fondo */
}

.features h2 {
    text-align: center;
    margin-bottom: 50px;
    color: #ffcc00; /* Color llamativo */
    font-size: 2.5rem;
}

.features .feature {
    text-align: center;
    margin-bottom: 30px;
    color: white;
}

.features .feature img {
    width: 120px;
    margin-bottom: 20px;
    border-radius: 50%; /* Imágenes redondeadas */
}


.models .feature img {
    width: 100% !important; /* Hace que las imágenes se ajusten al tamaño del contenedor */
    height: auto !important; /* Altura automática para mantener la proporción de la imagen */
    max-height: 400px !important; /* Altura máxima para las imágenes (ajusta este valor según sea necesario) */
    object-fit: cover !important; /* Ajusta la imagen sin distorsión */
    border: 3px solid #004b6b !important; /* Borde azul oscuro */
    border-radius: 15px !important; /* Bordes redondeados */
    margin-bottom: 20px !important; /* Separación entre imagen y texto */
}

/* 
Para cambiar el tamaño de las imágenes, ajusta el valor de 'max-height'.
Por ejemplo, puedes aumentar o disminuir 'max-height: 400px;' según lo que necesites.
*/

/* Sección ¿Quiénes Somos? */
.quienes-somos {
    padding: 50px 0;
    background-color: #005f87; /* Color de fondo */
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
}

.quienes-somos h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #ffcc00; /* Color llamativo */
    font-size: 2.5rem;
}

.quienes-somos p {
    text-align: center;
    font-size: 1.2rem;
    margin: 0 20px;
    color: white;
}

/* Formulario de contacto */
.contact-form {
    background-color: #005f87; /* Color más oscuro */
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); /* Sombra */
    margin-top: 50px;
}

.contact-form h3 {
    color: #ffcc00;
    margin-bottom: 30px;
}

.contact-form input, .contact-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: none;
}

.contact-form button {
    background-color: #ffcc00;
    color: #333;
    padding: 15px 30px;
    font-size: 1rem;
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease;
}

.contact-form button:hover {
    background-color: #ffb700;
    transform: scale(1.05);
}

/* Footer */
footer {
    background-color: #004b6b;
    color: #fff;
    padding: 30px 0;
    text-align: center;
}

footer p {
    margin: 0;
    font-size: 0.9rem;
}

footer a {
    color: #ffcc00;
    text-decoration: none;
    transition: color 0.3s ease;
}

footer a:hover {
    color: #ffb700;
}

/* Media Queries */
@media (max-width: 768px) {
    .header h1 {
        font-size: 1.2rem;
    }

    .header a {
        font-size: 1rem;
    }

    .hero h1 {
        font-size: 2.5rem;
    }

    .hero p {
        font-size: 1.2rem;
    }

    .btn-main {
        font-size: 1rem;
        padding: 10px 20px;
    }

    .features h2, .quienes-somos h2 {
        font-size: 2rem;
    }

    .quienes-somos p {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .hero h1 {
        font-size: 2rem;
    }

    .hero p {
        font-size: 1rem;
    }

    .features h2 {
        font-size: 1.8rem;
    }

    .features .feature img {
        width: 100px;
    }

    .contact-form h3 {
        font-size: 1.5rem;
    }

    .contact-form input, .contact-form textarea {
        font-size: 0.9rem;
    }

    .btn-main {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
}
</style>
    </style>
</head>
<body>

<div class="header">
    <h1>E-Skate</h1>
    <div>
        <a href="#quienes-somos">¿Quiénes Somos?</a>
        <a href="#contactanos">Contáctanos</a>
        <a href="<?= site_url('login') ?>">Ingresar</a>
    </div>
</div>

<!-- Sección Hero -->
<section class="hero">
    <div class="container">
        <h1>Revoluciona tu Movimiento</h1>
        <p>Explora el futuro del transporte con nuestras innovadoras e-skates.</p>
        <a href="<?= site_url('login') ?>" class="btn btn-main">Inicia Sesión para Monitorear tu Skate</a> <!-- Enlace a la página de login -->
    </div>
</section>


<!-- Sección ¿Quiénes Somos? -->
<section id="quienes-somos" class="quienes-somos">
    <div class="container">
        <h2>¿Quiénes Somos?</h2>
        <p>En E-Skate, somos una empresa dedicada a desarrollar tu transporte del futuro. Creamos e-skates, patinetas eléctricas recién traídas del futuro, que transforman la manera en que te desplazas.</p>
        <p>Con nuestra tecnología avanzada y diseño innovador, buscamos ofrecerte la mejor experiencia de movilidad, combinando velocidad, durabilidad y conectividad inteligente.</p>
        <p>Únete a la revolución del transporte y descubre cómo podemos hacer tu vida más fácil y emocionante con nuestros productos de última generación.</p>
    </div>
</section>

<!-- Sección de Modelos -->
<section class="models">
    <div class="container">
        <h2 class="text-center">Nuestros Modelos</h2> <!-- Título centrado -->
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
                <a href="<?= base_url('/index.php/skate/detail/2') ?>" class="btn btn-main">Comprar</a> <!-- Botón de compra -->
            </div>
            <div class="col-md-4 feature">
                <img src="https://imgs.search.brave.com/4hfX1Aw6h9uwaa7HX6i2vtgTdUT3mvVz1GoT5ojtQQE/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NDFNMnd5YTMzMEwu/anBn" alt="Modelo 3">
                <h4>Modelo E-Skate 3</h4>
                <p>Precio: $699</p>
                <a href="<?= base_url('/index.php/skate/detail/3') ?>" class="btn btn-main">Comprar</a> <!-- Botón de compra -->
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

</body>
</html>
