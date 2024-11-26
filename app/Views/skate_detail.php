<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
       }

       /* Header */
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
       }

       .header h1 {
           color: #ffcc00;
           margin: 0;
           font-size: 1.5rem;
       }

       .header a {
           color: #ffcc00;
           text-decoration: none;
           margin-left: 20px;
           font-size: 1.2rem;
           transition: color 0.3s ease;
       }

       .header a:hover {
           color: #ffb700;
       }

       /* Contenedor principal */
       .product-container {
           background-color: #005f87;
           padding: 30px;
           border-radius: 10px;
           margin-top: 120px; /* Ajuste de margen para header */
           box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
       }

       /* Imagen y detalles del producto */
       .product-image {
           width: 100%;
           max-height: 400px;
           object-fit: contain;
           border: 3px solid #004b6b;
           border-radius: 15px;
           box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
           margin-bottom: 20px;
       }

       /* Detalles del producto */
       .product-title {
           color: #ffcc00;
           font-size: 2.5rem;
           font-weight: 700;
           margin-bottom: 20px;
       }

       .product-price {
           color: #ffcc00;
           font-size: 2rem;
           font-weight: 700;
           margin-bottom: 20px;
       }

       .product-description {
           font-size: 1.2rem;
           color: #fff;
       }

       .btn-buy {
           background-color: #ffcc00;
           color: #333;
           padding: 15px 30px;
           font-size: 1.2rem;
           border-radius: 50px;
           border: none;
           transition: all 0.3s ease;
           box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
           margin-top: 20px;
           width: 100%;
       }

       .btn-buy:hover {
           background-color: #ffb700;
           transform: scale(1.05);
       }

       /* Estilos de la ventana de compra */
.purchase-panel {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%; /* Más estrecho en pantallas pequeñas */
    max-width: 500px; /* Ancho máximo para pantallas grandes */
    background-color: #005f87;
    color: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 2000;
    display: none; /* Para ocultarlo inicialmente */
}

.purchase-panel.active {
    display: block; /* Mostrar el panel cuando se activa */
    animation: fadeIn 0.5s ease; /* Animación de entrada */
}

/* Animación de aparición */
@keyframes fadeIn {
    from { opacity: 0; transform: translate(-50%, -60%); }
    to { opacity: 1; transform: translate(-50%, -50%); }
}

       .close-btn {
           color: #ffcc00;
           font-size: 1.5rem;
           background: none;
           border: none;
           float: right;
           cursor: pointer;
       }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>E-Skate</h1>
        <div>
            <a href="<?= site_url('login') ?>">Ingresar</a>
            <a href="<?= site_url('primerpagina') ?>">Volver atras</a>
        </div>
    </div>

    <div class="container">
        <!-- Encabezado del producto -->
        <div class="product-container row">
            <div class="col-md-6">
                <img src="<?= $modelo['imagen'] ?>" alt="Modelo E-Skate <?= $modelo['id'] ?>" class="product-image">
            </div>
            <div class="col-md-6">
                <h1 class="product-title">Modelo <?= $modelo['nombre'] ?></h1>
                <p class="product-price">Precio: <?= $modelo['precio'] ?></p>

                <a class="btn btn-main btn-buy" href="<?= site_url('comprar') ?>">Comprar</a>
            </div>

        </div>

        <!-- Descripción del producto -->
        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="text-white">Descripción del Producto</h4>
                <p class="product-description"><?= $modelo['descripcion'] ?></p>
            </div>
        </div>

        <!-- Otros modelos -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="text-white">Modelos Relacionados</h4>
                <div class="row">
                    <?php foreach ($otrosModelos as $otroModelo): ?>
                        <div class="col-md-4 text-center">
                            <img src="<?= $otroModelo['imagen'] ?>" alt="Modelo E-Skate <?= $otroModelo['id'] ?>" class="product-image" style="max-height: 150px; object-fit: contain;">
                            <h5 class="related-model-title"><?= $otroModelo['nombre'] ?></h5>
                            <p class="related-model-price"><?= $otroModelo['precio'] ?></p>
                            <a href="<?= site_url('/skate/detail/' . $otroModelo['id']) ?>" class="btn btn-main btn-buy related-model-button">Ver Detalles</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>