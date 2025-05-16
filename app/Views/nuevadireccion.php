<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dirección | E-Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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

        .address-form {
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

        .address-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00, #ffb700);
        }

        .address-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffcc00;
            font-size: 2rem;
            font-family: 'Baskervville', serif;
            position: relative;
        }

        .address-form h2::after {
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

        .address-form label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: 500;
        }

        .address-form input[type="text"],
        .address-form input[type="number"],
        .address-form input[list] {
            width: 100%;
            padding: 15px 20px;
            border-radius: 8px;
            border: 2px solid #004b6b;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 1rem;
            color: #333;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .address-form input[type="text"]:focus,
        .address-form input[type="number"]:focus,
        .address-form input[list]:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
        }

        .address-form button[type="submit"] {
            background-color: #ffcc00;
            color: #333;
            padding: 15px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            transition: all 0.4s ease;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .address-form button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffb700;
            transition: width 0.4s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .address-form button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
        }

        .address-form button[type="submit"]:hover::before {
            width: 100%;
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
            .address-form {
                padding: 30px 20px;
            }
            
            .address-form h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="address-form">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <h2>Agregar Dirección</h2>

        <form id="registrationForm" method="post" action="<?= site_url('direccion/guardarNueva') ?>">
            <div class="input-container">
                <label for="calle">Calle</label>
                <input type="text" id="calle" name="calle" placeholder="Nombre de la calle" value="<?= old('calle') ?>" required>
            </div>

            <div class="input-container">
                <label for="numero">Número</label>
                <input type="number" id="numero" name="numero" placeholder="Número" value="<?= old('numero') ?>" required>
            </div>

            <div class="input-container">
                <label for="provinciaInput">Provincia</label>
                <input list="provinciasList" id="provinciaInput" name="provincia" placeholder="Seleccionar provincia" value="<?= old('provincia') ?>" required>
                <datalist id="provinciasList">
                    <?php foreach ($provincias as $provincia): ?>
                        <option value="<?= $provincia['provincia'] ?>" data-id="<?= $provincia['id'] ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <div class="input-container">
                <label for="localidadInput">Localidad</label>
                <input list="localidadesList" id="localidadInput" name="localidad" placeholder="Seleccionar localidad" value="<?= old('localidad') ?>" required>
                <datalist id="localidadesList">
                    <!-- Las localidades se llenarán dinámicamente con JavaScript -->
                </datalist>
            </div>

            <button type="submit">Guardar Dirección</button>
        </form>
    </div>

    <script>
        document.getElementById('provinciaInput').addEventListener('change', function() {
            var provinciaValue = this.value;
            var provinciaOption = document.querySelector(`#provinciasList option[value="${provinciaValue}"]`);
            
            if (provinciaOption) {
                var provinciaId = provinciaOption.getAttribute('data-id');
                
                if (provinciaId) {
                    fetch(`<?= site_url('obtener-localidades') ?>/${provinciaId}`)
                        .then(response => response.json())
                        .then(data => {
                            const localidadInput = document.getElementById('localidadesList');
                            localidadInput.innerHTML = '';

                            if (data.error) {
                                console.error(data.error);
                            } else {
                                data.forEach(localidad => {
                                    let option = document.createElement('option');
                                    option.value = localidad.localidad;
                                    localidadInput.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener localidades:', error);
                        });
                }
            }
        });
    </script>
</body>

</html>

