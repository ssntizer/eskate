<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rutas públicas (sin autenticación)
$routes->get('/', 'AuthController::login'); // La ruta raíz sigue siendo el login para la PWA
$routes->get('/login', 'AuthController::login');
$routes->post('/loginUser', 'AuthController::loginUser');
$routes->get('/register', 'AuthController::register');
$routes->post('/registerUser', 'AuthController::registerUser');
$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->post('passwordreset/request', 'PasswordResetController::requestReset');
$routes->get('passwordreset/reset/(:any)', 'PasswordResetController::reset/$1');
$routes->post('passwordreset/update', 'PasswordResetController::updatePassword');
// Esta ruta de primerpagina se mantiene como pública si aún no la hemos decidido mover
$routes->get('/primerpagina', 'AuthController::primerpag');
$routes->post('enviarmail', 'AuthController::enviarmail');

// Rutas específicas de la PWA que podrían no necesitar autenticación o ser manejadas de otra forma
$routes->get('/manifest.json', 'AuthController::manifest');
$routes->get('/icons/eskate192x192.png', 'AuthController::eskate192');
$routes->get('/icons/eskate512x512.png', 'AuthController::eskate512');
$routes->get('/instalarpwa', 'AuthController::instalarpwa');

// Grupo de rutas protegidas (requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Rutas que ya existían en la PWA y ahora requieren autenticación
    $routes->get('/list-skates', 'AuthController::listSkates');
    $routes->get('/view-skate/(:any)', 'AuthController::viewSkate/$1');
    $routes->get('/logout', 'AuthController::logout');
    $routes->post('/add-skate', 'AuthController::addSkate');
    $routes->post('/unlink-skate/(:any)', 'AuthController::unlinkSkate/$1');
    $routes->get('skate/detail/(:num)', 'AuthController::detail/$1');
    $routes->post('update-skate-apodo', 'AuthController::updateSkateApodo');
    $routes->post('/deleteapodo/(:any)', 'AuthController::deleteapodo/$1');
    $routes->get('comprar', 'AuthController::comprar');
     $routes->get('profile', 'AuthController::profile');
    $routes->get('nuevadireccion', 'AuthController::guardar');
    $routes->post('/direccion/guardarNueva', 'AuthController::guardarNueva');
    $routes->get('/obtener-localidades/(:num)', 'AuthController::obtenerLocalidadesPorProvincia/$1');
     $routes->get('trayectoria', 'AuthController::trayectoria');
    // Las rutas de PayPal también deben ser protegidas si solo usuarios logueados pueden comprar
    $routes->post("paypal/createOrder", "PaypalController::createOrder");
    $routes->post("paypal/captureOrder", "PaypalController::captureOrder");
});

// Rutas del SkateController (si necesitan autenticación, deberías moverlas al grupo)
$routes->post('/skate/update', 'SkateController::updateSkateData');