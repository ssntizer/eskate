<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
            font-size: 2rem;
        }

        .register-form input,
        .register-form select {
            width: 100%;
            height: 50px;
            margin-bottom: 20px;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .register-form input:focus,
        .register-form select:focus {
            border-color: #00e5ff;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.5);
        }

        .register-form button {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .register-form .btn-tarjeta {
            background-color: #ff6600;
            color: white;
        }

        .register-form .btn-tarjeta:hover {
            background-color: #e65c00;
            transform: scale(1.05);
        }

        .register-form .btn-paypal {
            background-color: #003087;
            color: white;
            margin-top: 10px;
        }

        .register-form .btn-paypal:hover {
            background-color: #001f5b;
            transform: scale(1.05);
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

        .register-form a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            transition: color 0.3s ease;
        }

        .register-form a:hover {
            color: #00e5ff;
        }
    </style>
</head>

<body>
    <div class="register-form">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <h2>Compra Segura</h2>

        <select id="paymentMethod" name="payment_method" required>
            <option value="tarjeta" selected>Pagar con Tarjeta</option>
            <option value="paypal">Pagar con PayPal</option>
        </select>

        <form id="purchaseForm" method="post" action="<?= site_url('processPurchase') ?>">
            
            <!-- Siempre visible: campo de email -->
            <input type="email" name="email" placeholder="Correo Electrónico" value="<?= old('email') ?>" required>

            <div id="tarjetaFields">
                <input type="text" name="card_number" placeholder="Número de Tarjeta" maxlength="16" pattern="\d{16}" required>
                <input type="text" name="cardholder_name" placeholder="Nombre en la Tarjeta" required>
                <input type="text" name="expiration_date" placeholder="Fecha de Expiración (MM/AA)" pattern="\d{2}/\d{2}" required>
                <input type="password" name="security_code" placeholder="Código de Seguridad (CVV)" maxlength="3" pattern="\d{3}" required>
            </div>

            <select name="address_id" required>
                <option value="" disabled selected>Seleccione su dirección</option>
                <?php if (!empty($userAddresses)): ?>
                    <?php foreach ($userAddresses as $address): ?>
                        <option value="<?= $address['ID_direccion'] ?>">
                            <?= $address['calle'] ?> (<?= $address['numero'] ?>), <?= $address['localidad_nombre'] ?>, <?= $address['provincia_nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No tienes direcciones registradas</option>
                <?php endif; ?>
            </select>

            <button type="submit" class="btn-tarjeta">Pagar con Tarjeta</button>
        </form>

        <button id="paypalButton" class="btn-paypal" style="display: none;">Pagar con PayPal</button>

        <a href="<?= site_url('nuevadireccion') ?>">Registrar nueva dirección</a>
        <a href="<?= site_url('/') ?>">Volver al inicio</a>
    </div>

    <script>
        $(document).ready(function() {
            // Cambia la vista entre métodos de pago
            $("#paymentMethod").change(function() {
                if ($(this).val() === "paypal") {
                    $("#tarjetaFields").hide();  // Esconde campos de tarjeta
                    $("#purchaseForm button").hide();  // Esconde el botón de tarjeta
                    $("#paypalButton").show();  // Muestra el botón de PayPal
                } else {
                    $("#tarjetaFields").show();  // Muestra campos de tarjeta
                    $("#purchaseForm button").show();  // Muestra el botón de tarjeta
                    $("#paypalButton").hide();  // Esconde el botón de PayPal
                }
            });

            // Maneja el clic en el botón de PayPal
            $("#paypalButton").click(function(e) {
                e.preventDefault();  // Previene que el formulario se envíe

                var address_id = $("select[name='address_id']").val();
                var email = $("input[name='email']").val();

                // Validación
                if (!address_id) {
                    alert("Por favor, seleccione una dirección.");
                    return;
                }

                // Validación del email
                if (!email) {
                    alert("Por favor, ingrese un correo electrónico.");
                    return;
                }

                // Llamada AJAX para simular el pago
                $.ajax({
                    url: "<?= site_url('PaypalController/simularPagoPayPal') ?>",  // Asegúrate de que esta URL sea la correcta
                    type: "POST",
                    data: {
                        usuario_id: 1,  // Pasa el ID del usuario real
                        monto: 100,  // Monto simulado, ajústalo a la lógica real
                        email: email,
                        address_id: address_id
                    },
                    dataType: "json",
                    success: function(response) {
                        alert(response.message);
                        if (response.status === "success") {
                            window.location.href = "<?= site_url('successPage') ?>";  // Redirige a la página de éxito
                        } else {
                            alert("Error en el pago. Intenta de nuevo.");
                        }
                    },
                    error: function() {
                        alert("Error en la conexión. Intenta de nuevo.");
                    }
                });
            });
        });
    </script>
</body>

</html>
