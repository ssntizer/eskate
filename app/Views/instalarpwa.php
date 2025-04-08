<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar PWA</title>
    <link rel="manifest" href="/manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

/* Estilos generales */
body {
    background-color: #00719c;
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    color: #ffffff;
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Header mejorado */
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

/* Contenedor de instalación */
.install-container {
    background-color: rgba(0, 95, 135, 0.9);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 500px;
    text-align: center;
    position: relative;
    overflow: hidden;
    border: 1px solid #004b6b;
}

.install-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #ffcc00, #ffb700);
}

.install-container h2 {
    color: #ffcc00;
    font-size: 2.2rem;
    margin-bottom: 30px;
    font-family: 'Baskervville', serif;
    position: relative;
}

.install-container h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
}

.install-container p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    line-height: 1.6;
}

/* Botón de instalación */
#installPWA {
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
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
    cursor: pointer;
}

#installPWA::before {
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

#installPWA:hover {
    color: #333;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
}

#installPWA:hover::before {
    width: 100%;
}

#installPWA:active {
    transform: translateY(-1px);
}

/* Mensaje de estado */
.install-message {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    font-size: 1.1rem;
    display: none;
}

.success-message {
    background-color: rgba(40, 167, 69, 0.2);
    color: #d4edda;
    border: 1px solid #28a745;
}

.error-message {
    background-color: rgba(220, 53, 69, 0.2);
    color: #f8d7da;
    border: 1px solid #dc3545;
}

/* Media Queries */
@media (max-width: 768px) {
    .install-container {
        padding: 30px 20px;
    }
    
    .install-container h2 {
        font-size: 1.8rem;
    }
    
    .install-container p {
        font-size: 1.1rem;
    }
    
    #installPWA {
        padding: 12px 25px;
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .install-container {
        padding: 25px 15px;
    }
    
    .install-container h2 {
        font-size: 1.6rem;
    }
    
    .install-container p {
        font-size: 1rem;
    }
}
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>E-Skate</h1>
        <div class="nav-links">
            <a href="<?= site_url('login') ?>">Ingresar</a>
            <a href="<?= site_url('primerpagina') ?>">Volver atrás</a>
        </div>
    </div>

    <div class="install-container">
        <h2>Instalar E-Skate PWA</h2>
        <p>Haz clic en el botón para instalar la aplicación y acceder a todas las funciones incluso sin conexión.</p>
        <button id="installPWA">Instalar Aplicación</button>
        <div id="installMessage" class="install-message"></div>
    </div>

    <script>
        let installEvent;

        // Detectar si la PWA es instalable
        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault(); // Evitar la instalación automática
            installEvent = event;   // Guardar el evento para usarlo después
            document.getElementById('installPWA').style.display = 'block'; // Mostrar el botón
        });

        document.getElementById('installPWA').addEventListener('click', () => {
            if (installEvent) {
                installEvent.prompt(); // Disparar la instalación manual

                installEvent.userChoice.then((choiceResult) => {
                    const messageDiv = document.getElementById('installMessage');
                    messageDiv.style.display = 'block';
                    
                    if (choiceResult.outcome === 'accepted') {
                        messageDiv.textContent = '¡Aplicación instalada con éxito!';
                        messageDiv.className = 'install-message success-message';
                        console.log('El usuario instaló la PWA');
                    } else {
                        messageDiv.textContent = 'Instalación cancelada por el usuario';
                        messageDiv.className = 'install-message error-message';
                        console.log('El usuario canceló la instalación');
                    }

                    // Avisar a la página 1 para cerrar la pestaña
                    if (window.opener) {
                        window.opener.postMessage("cerrarPestana", "*");
                    }

                    // Cerrar la pestaña automáticamente después de 1.5 segundos
                    setTimeout(() => {
                        window.close();
                    }, 1500);
                });
            } else {
                const messageDiv = document.getElementById('installMessage');
                messageDiv.style.display = 'block';
                messageDiv.textContent = 'La aplicación ya está instalada o no es compatible con tu dispositivo.';
                messageDiv.className = 'install-message error-message';
            }
        });

        // Ocultar el botón si no hay evento de instalación
        window.addEventListener('load', () => {
            if (!installEvent) {
                document.getElementById('installPWA').style.display = 'none';
                const messageDiv = document.getElementById('installMessage');
                messageDiv.style.display = 'block';
                messageDiv.textContent = 'La aplicación ya está instalada o no es compatible con tu dispositivo.';
                messageDiv.className = 'install-message error-message';
            }
        });
    </script>
</body>
</html>