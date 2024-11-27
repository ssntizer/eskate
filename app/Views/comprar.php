<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <form id="purchaseForm" method="post" action="<?= site_url('processPurchase') ?>">
            <input type="text" name="card_number" placeholder="Número de Tarjeta" maxlength="16" pattern="\d{16}" required>
            <input type="text" name="cardholder_name" placeholder="Nombre en la Tarjeta" required>
            <input type="text" name="expiration_date" placeholder="Fecha de Expiración (MM/AA)" pattern="\d{2}/\d{2}" required>
            <input type="password" name="security_code" placeholder="Código de Seguridad (CVV)" maxlength="3" pattern="\d{3}" required>
            <input type="email" name="email" placeholder="Correo Electrónico" value="<?= old('email') ?>" required>

            <!-- Campo de direcciones -->
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

            <button type="submit">Pagar</button>
        </form>
        <a id="bl" href="<?= site_url('nuevadireccion') ?>">Registrar nueva dirección</a>
        <a id="bl" href="<?= site_url('/') ?>">Volver al inicio</a>
    </div>

    <script>
        document.getElementById('purchaseForm').addEventListener('submit', function (event) {
            const cardNumber = this.card_number.value.replace(/\s+/g, '');
            const expirationDate = this.expiration_date.value;
            const cvv = this.security_code.value;

            const expirationRegex = /^\d{2}\/\d{2}$/;
            if (!expirationRegex.test(expirationDate)) {
                alert('Fecha de expiración no válida. Use el formato MM/AA.');
                event.preventDefault();
            }

            if (!/^\d{16}$/.test(cardNumber)) {
                alert('El número de tarjeta debe contener 16 dígitos.');
                event.preventDefault();
            }

            if (!/^\d{3}$/.test(cvv)) {
                alert('El CVV debe contener 3 dígitos.');
                event.preventDefault();
            }
        });
    </script>
</body>

</html>