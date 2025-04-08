<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ&currency=USD"></script>
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
    padding-top: 70px;
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

/* Formulario de compra */
.purchase-container {
    background-color: rgba(0, 95, 135, 0.8);
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

.purchase-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #ffcc00, #ffb700);
}

.purchase-container h2 {
    color: #ffcc00;
    font-size: 2.2rem;
    margin-bottom: 30px;
    font-family: 'Baskervville', serif;
    position: relative;
}

.purchase-container h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
}

/* Contenedor del botón de PayPal */
#paypal-button-container {
    margin-top: 30px;
    min-height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Mensajes de transacción */
#transactionResult {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    display: none;
    font-size: 1.1rem;
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

/* Botón de volver */
.back-link {
    display: inline-block;
    margin-top: 30px;
    color: #ffcc00;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.back-link:hover {
    color: #ffb700;
    transform: translateX(-5px);
}

.back-link::before {
    content: '←';
    position: absolute;
    left: -20px;
    opacity: 0;
    transition: all 0.3s ease;
}

.back-link:hover::before {
    opacity: 1;
    left: -15px;
}

/* Media Queries */
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
    }
    
    .nav-links a {
        padding: 6px 12px;
        font-size: 1rem;
    }
    
    .purchase-container {
        padding: 30px 20px;
    }
    
    .purchase-container h2 {
        font-size: 1.8rem;
    }
}

@media (max-width: 576px) {
    body {
        padding-top: 100px;
    }
    
    .purchase-container {
        padding: 25px 15px;
    }
    
    .purchase-container h2 {
        font-size: 1.6rem;
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

    <div class="purchase-container">
        <h2>Compra Segura</h2>
        
        <!-- Contenedor para el botón de PayPal -->
        <div id="paypal-button-container"></div>
        
        <!-- Mensaje de resultado de transacción -->
        <div id="transactionResult" class="success-message" style="display: none;"></div>
        
        <a href="<?= site_url('primerpagina') ?>" class="back-link">Volver a la tienda</a>
    </div>

    <script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch("<?= base_url('paypal/createOrder') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    amount: "59.99" // Monto de la transacción
                }),
            })
            .then(response => response.json())
            .then(order => order.id);
        },

        onApprove: function(data, actions) {
            return fetch("<?= base_url('paypal/captureOrder') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    orderID: data.orderID
                }),
            })
            .then(response => response.json())
            .then(order => {
                const resultDiv = document.getElementById('transactionResult');
                resultDiv.style.display = 'block';
                resultDiv.textContent = "Pago realizado con éxito. Muchas gracias! En instantes le llegará un mail a la dirección ingresada para la compra.";
                resultDiv.className = 'success-message';
            })
            .catch(error => {
                const resultDiv = document.getElementById('transactionResult');
                resultDiv.style.display = 'block';
                resultDiv.textContent = "Error al procesar el pago. Por favor intente nuevamente.";
                resultDiv.className = 'error-message';
                console.error("Error al capturar el pago:", error);
            });
        }
    }).render("#paypal-button-container");
    </script>
</body>
</html>