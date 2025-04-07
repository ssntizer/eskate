<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra | E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #00719c;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .purchase-form {
            width: 90%;
            max-width: 500px;
            padding: 40px 30px;
            background-color: #005f87;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid #004b6b;
            position: relative;
            overflow: hidden;
        }

        .purchase-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .purchase-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffcc00;
            font-size: 2rem;
            font-family: 'Baskervville', serif;
            position: relative;
        }

        .purchase-form h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
        }

        .input-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .purchase-form input,
        .purchase-form select {
            width: 100%;
            padding: 15px 20px;
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            color: #333;
        }

        .purchase-form input:focus,
        .purchase-form select:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        .payment-method {
            margin-bottom: 25px;
        }

        .payment-method select {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
        }

        .btn-payment {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            margin-bottom: 15px;
        }

        .btn-payment::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            transition: width 0.4s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .btn-card {
            background-color: #ffcc00;
            color: #333;
        }

        .btn-card::before {
            background-color: #ffb700;
        }

        .btn-paypal {
            background-color: #003087;
            color: white;
        }

        .btn-paypal::before {
            background-color: #001f5b;
        }

        .btn-payment:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-payment:hover::before {
            width: 100%;
        }

        .purchase-links {
            margin-top: 20px;
            text-align: center;
        }

        .purchase-links a {
            color: #ffcc00;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            display: block;
            margin: 12px 0;
        }

        .purchase-links a:hover {
            color: #ffb700;
            text-decoration: underline;
        }

        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            color: #6bff6b;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .purchase-form {
                padding: 30px 20px;
            }
            
            .purchase-form h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="purchase-form">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <h2>Compra Segura</h2>

        <div class="payment-method">
            <select id="paymentMethod" name="payment_method" required>
                <option value="tarjeta" selected>Pagar con Tarjeta</option>
                <option value="paypal">Pagar con PayPal</option>
            </select>
        </div>

        <form id="purchaseForm" method="post" action="<?= site_url('processPurchase') ?>">
            <div class="input-container">
                <input type="email" name="email" placeholder="Correo Electrónico" value="<?= old('email') ?>" required>
            </div>

            <div id="tarjetaFields">
                <div class="input-container">
                    <input type="text" name="card_number" placeholder="Número de Tarjeta" maxlength="16" pattern="\d{16}" required>
                </div>
                <div class="input-container">
                    <input type="text" name="cardholder_name" placeholder="Nombre en la Tarjeta" required>
                </div>
                <div class="input-container">
                    <input type="text" name="expiration_date" placeholder="Fecha de Expiración (MM/AA)" pattern="\d{2}/\d{2}" required>
                </div>
                <div class="input-container">
                    <input type="password" name="security_code" placeholder="Código de Seguridad (CVV)" maxlength="3" pattern="\d{3}" required>
                </div>
            </div>

            <div class="input-container">
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
            </div>

            <button type="submit" class="btn-payment btn-card">Pagar con Tarjeta</button>
        </form>

        <button id="paypalButton" class="btn-payment btn-paypal" style="display: none;">Pagar con PayPal</button>

        <div class="purchase-links">
            <a href="<?= site_url('nuevadireccion') ?>">Registrar nueva dirección</a>
            <a href="<?= site_url('/') ?>">Volver al inicio</a>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $("#paymentMethod").change(function() {
            if ($(this).val() === "paypal") {
                $("#tarjetaFields").hide();
                $("#purchaseForm button").hide();
                $("#paypalButton").show();
                $("input[name='email']").parent().show();
            } else {
                $("#tarjetaFields").show();
                $("#purchaseForm button").show();
                $("#paypalButton").hide();
            }
        });

        $("#paypalButton").click(function() {
            var address_id = $("select[name='address_id']").val();
            var email = $("input[name='email']").val();

            if (!address_id) {
                alert("Por favor, seleccione una dirección.");
                return;
            }
            if (!email) {
                alert("Por favor, ingrese su correo electrónico.");
                return;
            }

            $.ajax({
                url: "<?= site_url('PaypalController/simularPagoPayPal') ?>",
                type: "POST",
                data: {
                    monto: 100, // Simulación
                    email: email,
                    direccion: address_id
                },
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status === "success") {
                        window.location.href = "<?= site_url('') ?>";
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Hubo un error en la conexión con el servidor.");
                }
            });
        });
    });
    </script>
</body>
</html>