<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ&currency=USD"></script>
    <style>
        /* Estilos reutilizados */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Permanent+Marker&display=swap');

        body {
            background-color: #00719c;
            color: #ffffff;
            font-family: "Baskervville SC", serif;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-form {
            width: 90%;
            max-width: 400px;
            padding: 40px;
            border-radius: 15px;
            background: linear-gradient(145deg, #006f99, #008dc2);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #fff;
        }

        .register-form h2 {
            margin-bottom: 20px;
            font-family: "Baskervville SC", static;
            font-size: 2rem;
        }

        .register-form input, .register-form select {
            width: 100%;
            height: 50px;
            margin-bottom: 20px;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .register-form input:focus, .register-form select:focus {
            border-color: #00e5ff;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.5);
        }

        .register-form button[type="submit"] {
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

        .register-form button[type="submit"]:hover {
            background-color: #e65c00;
            transform: scale(1.05);
        }

        .register-form button[type="submit"]:active {
            transform: scale(0.98);
        }

        .error,
        .success {
            font-size: 14px;
            margin-bottom: 20px;
            text-align: left;
        }

        .error {
            color: #e74c3c;
        }

        .success {
            color: #2ecc71;
        }

        .register-form a#bl {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            transition: color 0.3s ease;
        }

        .register-form a#bl:hover {
            color: #00e5ff;
        }

        #transactionResult {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="register-form">
        <h2>Compra Segura</h2>

        <!-- Contenedor para el botón de PayPal -->
        <div id="paypal-button-container"></div>
    </div>

    <script>
    // Renderizar el botón de PayPal
    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'gold',
            shape: 'rect',
            label: 'paypal'
        },
        
        createOrder: function(data, actions) {
            return fetch("<?= base_url('paypal/createOrder') ?>", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ 
                    amount: "59.99",
                    description: "Compra en IRConnect" // Opcional: agregar más datos
                }) 
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (!data.id) {
                    throw new Error("No se recibió un ID de orden válido");
                }
                return data.id;
            })
            .catch(error => {
                console.error("Error al crear la orden:", error);
                alert("Error al procesar el pago. Por favor intente nuevamente.");
                throw error; // Esto detiene el flujo de PayPal
            });
        },

        onApprove: function(data, actions) {
            return fetch("<?= base_url('paypal/captureOrder') ?>", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ 
                    orderID: data.orderID 
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(orderData => {
                // Verificar si el pago fue aprobado
                if (orderData.status === "COMPLETED") {
                    // Redirigir o mostrar mensaje de éxito
                    alert("¡Pago completado con éxito! Recibirá un correo de confirmación.");
                    
                    // Opcional: redirigir a página de éxito
                    // window.location.href = "<?= base_url('gracias') ?>";
                } else {
                    throw new Error("El estado del pago no es COMPLETED");
                }
            })
            .catch(error => {
                console.error("Error al capturar el pago:", error);
                alert("Ocurrió un error al procesar su pago: " + (error.message || "Por favor intente nuevamente."));
            });
        },
        
        onError: function(err) {
            console.error("Error en el flujo de PayPal:", err);
            alert("Ocurrió un error con PayPal. Por favor intente nuevamente o elija otro método de pago.");
        },
        
        onCancel: function(data) {
            // El usuario canceló el pago
            console.log("Pago cancelado por el usuario:", data);
            // Puedes mostrar un mensaje opcional aquí
        }
        
    }).render("#paypal-button-container");
</script>

</body>
</html>