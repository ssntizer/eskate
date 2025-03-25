<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar PWA</title>
    <link rel="manifest" href="/manifest.json">
    <style>
        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: "Baskervville SC", static;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); /* Textura de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .install-container {
            width: 90%;
            max-width: 400px;
            padding: 40px;
            border-radius: 15px;
            background: linear-gradient(145deg, #006f99, #008dc2);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .install-container h2 {
            margin-bottom: 20px;
            font-family: "Baskervville SC", static;
            font-size: 2rem;
        }

        .install-container p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .install-container button {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background-color: #ff6600;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .install-container button:hover {
            background-color: #e65c00;
            transform: scale(1.05);
        }

        .install-container button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

    <div class="install-container">
        <h2>Instalar E-Skate PWA</h2>
        <p>Haz clic en el botón para instalar la aplicación.</p>
        <button id="installPWA">Instalar PWA</button>
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
                    if (choiceResult.outcome === 'accepted') {
                        console.log('El usuario instaló la PWA');
                    } else {
                        console.log('El usuario canceló la instalación');
                    }

                    // Avisar a la página 1 para cerrar la pestaña
                    window.opener.postMessage("cerrarPestana", "*");

                    // Cerrar la pestaña automáticamente después de 1.5 segundos
                    setTimeout(() => {
                        window.close();
                    }, 1500);
                });
            }
        });
    </script>

</body>
</html>