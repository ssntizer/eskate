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

        <div id="transactionResult"></div>

        <select id="paymentMethod" name="payment_method" required>
            <option value="tarjeta" selected>Pagar con Tarjeta</option>
            <option value="paypal">Pagar con PayPal</option>
        </select>

        <form id="purchaseForm" method="post" action="<?= site_url('processPurchase') ?>">
            <input type="hidden" name="user_id" value="<?= session('user_id') ?>">
            <input type="email" name="email" placeholder="Correo Electrónico" value="<?= old('email') ?>" required>

            <!-- Campos para tarjeta de crédito -->
            <div id="tarjetaFields">
                <input type="text" name="card_number" placeholder="Número de Tarjeta" maxlength="16" pattern="\d{16}" required>
                <input type="text" name="cardholder_name" placeholder="Nombre en la Tarjeta" required>
                <input type="text" name="expiration_date" placeholder="Fecha de Expiración (MM/AA)" pattern="\d{2}/\d{2}" required>
                <input type="password" name="security_code" placeholder="Código de Seguridad (CVV)" maxlength="3" pattern="\d{3}" required>
            </div>

            <!-- Dirección de envío -->
            <select name="address_id" required>
                <option value="" disabled selected>Seleccione su dirección</option>
                <?php foreach ($userAddresses as $address): ?>
                    <option value="<?= $address['ID_direccion'] ?>">
                        <?= $address['calle'] ?> (<?= $address['numero'] ?>), <?= $address['localidad_nombre'] ?>, <?= $address['provincia_nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-tarjeta">Pagar con Tarjeta</button>
        </form>

        <!-- Contenedor para el botón de PayPal -->
        <div id="paypal-button-container" style="display: none;"></div>
    </div>

    <script>
    $(document).ready(function() {
        // Mostrar u ocultar los campos según el método de pago seleccionado
        $("#paymentMethod").change(function() {
            if ($(this).val() === "paypal") {
                $("#tarjetaFields").hide();
                $("#purchaseForm button").hide();
                $("#paypal-button-container").show();
            } else {
                $("#tarjetaFields").show();
                $("#purchaseForm button").show();
                $("#paypal-button-container").hide();
            }
        });

        // Renderizar el botón de PayPal
        paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch("<?= base_url('index.php/paypal/createOrder') ?>", {
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
            return fetch("<?= base_url('index.php/paypal/captureOrder') ?>", {
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
                alert("Pago realizado con éxito. Muchas gracias! En instantes le llegará un mail a la dirección ingresada para la compra. " );
            })
            .catch(error => console.error("Error al capturar el pago:", error));
        }
    }).render("#paypal-button-container");})
</script>
</body>
</html>