<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Skate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

/* Estilos generales */
body {
    background-color: #00719c; /* Fondo de la página */
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    color: #ffffff; /* Texto blanco */
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    padding-top: 70px; /* Espacio para el header fijo */
}

/* Header mejorado */
.header {
    background-color: #005f87; /* Color de fondo del encabezado */
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid #004b6b;
    position: fixed; /* Fija el header en la parte superior */
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000; /* Asegura que esté siempre sobre el contenido */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.header h1 {
    color: #ffffff;
    margin: 0;
    font-size: 1.8rem;
    font-family: "Quicksand", sans-serif;
    font-weight: bold;
    letter-spacing: 1px;
}

.nav-links {
    display: flex;
    gap: 25px;
}

.nav-links a {
    color: #ffffff;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    padding: 8px 15px;
    border-radius: 30px;
    position: relative;
    font-weight: 500;
}

.nav-links a:hover {
    color: #ffcc00;
    background-color: rgba(255, 204, 0, 0.1);
}

.nav-links a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: #ffcc00;
    transition: width 0.3s ease;
}

.nav-links a:hover::after {
    width: 100%;
}

/* Contenedor principal */
.product-container {
    background-color: rgba(0, 95, 135, 0.7);
    padding: 40px;
    border-radius: 15px;
    margin-top: 30px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    border: 1px solid #004b6b;
}

/* Imagen y detalles del producto */
.product-image {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    border: 3px solid #004b6b;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.product-image:hover {
    transform: scale(1.02);
    border-color: #ffcc00;
}

/* Detalles del producto */
.product-title {
    color: #ffcc00;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
}

.product-price {
    color: #ffcc00;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 30px;
}

.product-description {
    font-size: 1.2rem;
    color: #fff;
    line-height: 1.8;
}

/* Botón de compra mejorado */
.btn-buy {
    background-color: #ffcc00;
    color: #333;
    padding: 15px 35px;
    font-size: 1.2rem;
    border-radius: 50px;
    border: none;
    transition: all 0.4s ease;
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3);
    font-weight: 600;
    position: relative;
    overflow: hidden;
    z-index: 1;
    width: 100%;
    margin-top: 20px;
}

.btn-buy::before {
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

.btn-buy:hover {
    color: #333;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 204, 0, 0.4);
}

.btn-buy:hover::before {
    width: 100%;
}

/* Sección de modelos relacionados */
.related-models {
    margin-top: 50px;
    padding: 30px 0;
    background-color: rgba(0, 95, 135, 0.5);
    border-radius: 15px;
}

.related-models h4 {
    color: #ffcc00;
    font-size: 1.8rem;
    margin-bottom: 30px;
    text-align: center;
    position: relative;
}

.related-models h4::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #ffcc00 50%, transparent 100%);
}

.related-model {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 15px;
    transition: all 0.3s ease;
    background-color: rgba(0, 75, 107, 0.3);
}

.related-model:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    background-color: rgba(0, 75, 107, 0.5);
}

.related-model-title {
    color: #ffcc00;
    font-size: 1.3rem;
    margin: 15px 0 10px;
}

.related-model-price {
    color: #ffffff;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.related-model-button {
    background-color: #ffcc00;
    color: #333;
    padding: 10px 25px;
    font-size: 1rem;
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease;
    display: inline-block;
    font-weight: 600;
    text-decoration: none;
}

.related-model-button:hover {
    background-color: #ffb700;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(255, 204, 0, 0.3);
    color: #333;
}

/* Estilos de la ventana de compra */
.purchase-panel {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    background-color: #005f87;
    background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
    color: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    z-index: 2000;
    display: none;
    border: 2px solid #ffcc00;
}

.purchase-panel.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

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
    transition: all 0.3s ease;
}

.close-btn:hover {
    transform: rotate(90deg);
    color: #ffb700;
}

/* Media Queries */
@media (max-width: 992px) {
    .product-title {
        font-size: 2.2rem;
    }
    
    .product-price {
        font-size: 1.8rem;
    }
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        padding: 15px 0;
    }
    
    .header h1 {
        margin-bottom: 15px;
        font-size: 1.8rem;
    }
    
    .nav-links {
        gap: 15px;
    }
    
    .nav-links a {
        padding: 6px 12px;
        font-size: 1rem;
    }
    
    .product-container {
        padding: 30px;
    }
    
    .product-title {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .product-container {
        padding: 20px;
    }
    
    .product-title {
        font-size: 1.8rem;
    }
    
    .product-price {
        font-size: 1.5rem;
    }
    
    .product-description {
        font-size: 1.1rem;
    }
    
    .btn-buy {
        padding: 12px 25px;
        font-size: 1rem;
    }
}
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>E-Skate</h1>
        <div class="nav-links">
            <a href="<?= site_url('login') ?>">Ingresar</a>
            <a href="<?= site_url('primerpagina') ?>">Volver atrás</a>
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
                <p class="product-description"><?= $modelo['descripcion'] ?></p>
                <a class="btn btn-buy" href="<?= site_url('comprar/' . $modelo['precio']) ?>">Comprar</a>
            </div>
        </div>

        <!-- Modelos relacionados -->
        <div class="related-models">
            <h4>Modelos Relacionados</h4>
            <div class="row">
                <?php foreach ($otrosModelos as $otroModelo): ?>
                    <div class="col-md-4">
                        <div class="related-model">
                            <img src="<?= $otroModelo['imagen'] ?>" alt="Modelo E-Skate <?= $otroModelo['id'] ?>" class="product-image" style="max-height: 150px;">
                            <h5 class="related-model-title"><?= $otroModelo['nombre'] ?></h5>
                            <p class="related-model-price"><?= $otroModelo['precio'] ?></p>
                            <a href="<?= site_url('/skate/detail/' . $otroModelo['id']) ?>" class="related-model-button">Ver Detalles</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Ventana de compra (se mantiene igual) -->
    <div class="purchase-panel" id="purchasePanel">
        <button class="close-btn" id="closeBtn">&times;</button>
        <!-- Contenido de la ventana de compra -->
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Scripts para la ventana de compra (se mantienen igual)
    </script>
</body>
</html>