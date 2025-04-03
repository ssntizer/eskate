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
document.addEventListener('DOMContentLoaded', function() {
    paypal.Buttons({
        style: {
            shape: 'rect',
            color: 'gold',
            layout: 'vertical',
            label: 'paypal'
        },
        
        createOrder: function(data, actions) {
            return fetch('/paypal/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: '10.00', // Puedes cambiar esto dinámicamente
                    currency: 'USD'
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error creating PayPal order');
                }
                return response.json();
            })
            .then(function(orderData) {
                if (!orderData.id) {
                    throw new Error('Invalid order ID from PayPal');
                }
                return orderData.id;
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('Error al crear la orden de PayPal. Por favor intenta nuevamente.');
            });
        },
        
        onApprove: function(data, actions) {
            return fetch(`/paypal/capture-order/${data.orderID}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error capturing PayPal payment');
                }
                return response.json();
            })
            .then(function(orderData) {
                // Redirigir a página de éxito o mostrar mensaje
                console.log('Capture result', orderData);
                
                // Verificar si el pago fue exitoso
                if (orderData.status === 'COMPLETED') {
                    window.location.href = '/paypal/success';
                } else {
                    alert('El pago no se completó correctamente.');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('Error al procesar el pago. Por favor intenta nuevamente.');
            });
        },
        
        onError: function(err) {
            console.error('PayPal Error:', err);
            alert('Ocurrió un error con PayPal. Por favor intenta nuevamente.');
        },
        
        onCancel: function(data) {
            console.log('Payment cancelled:', data);
            window.location.href = '/paypal/cancel';
        }
    }).render('#paypal-button-container');
});
</script>

</body>
</html>