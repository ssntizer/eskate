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
        createOrder: function(data, actions) {
            return fetch('/paypal/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: '10.00' // Cambia esto según tu producto
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(function(orderData) {
                if (!orderData.id) {
                    throw new Error('No order ID received from server');
                }
                return orderData.id; // Esto es lo que PayPal espera
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('Error creating order: ' + (error.message || 'Unknown error'));
                throw error; // Esto hace que PayPal muestre su mensaje de error
            });
        },
        
        onApprove: function(data, actions) {
            return fetch('/paypal/capture-order/' + data.orderID, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(function(orderData) {
                console.log('Capture result', orderData);
                alert('Transaction completed by ' + 
                    (orderData.payer.name.given_name || 'the buyer'));
                // Aquí puedes redirigir o actualizar tu UI
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('Error capturing payment: ' + 
                    (error.message || 'Unknown error'));
            });
        },
        
        onError: function(err) {
            console.error('PayPal Error:', err);
            alert('An error occurred with PayPal. Please try again.');
        }
    }).render('#paypal-button-container');
});
</script>

</body>
</html>