<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ&currency=USD"></script>
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
            <input type="hidden" name="user_id" value="<?= session('user_id') ?>">
            <input type="email" name="email" placeholder="Correo Electrónico" value="<?= old('email') ?>" required>

            <div id="tarjetaFields">
                <input type="text" name="card_number" placeholder="Número de Tarjeta" maxlength="16" pattern="\d{16}" required>
                <input type="text" name="cardholder_name" placeholder="Nombre en la Tarjeta" required>
                <input type="text" name="expiration_date" placeholder="Fecha de Expiración (MM/AA)" pattern="\d{2}/\d{2}" required>
                <input type="password" name="security_code" placeholder="Código de Seguridad (CVV)" maxlength="3" pattern="\d{3}" required>
            </div>

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

        <div id="paypal-button-container" style="display: none;"></div>
    </div>

    <script>
    $(document).ready(function() {
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

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '100.00'  // Aquí puedes cambiar el monto dinámicamente si es necesario
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    $.ajax({
                        url: "<?= site_url('PaypalController/procesarPago') ?>",
                        type: "POST",
                        data: {
                            order_id: data.orderID,
                            email: $("input[name='email']").val(),
                            user_id: $("input[name='user_id']").val(),
                            address_id: $("select[name='address_id']").val()
                        },
                        dataType: "json",
                        success: function(response) {
                            alert(response.message);
                            if (response.status === "success") {
                                window.location.href = "<?= site_url('') ?>";
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert("Hubo un error en la conexión con el servidor.");
                        }
                    });
                });
            }
        }).render('#paypal-button-container');
    });
    </script>
</body>
</html>