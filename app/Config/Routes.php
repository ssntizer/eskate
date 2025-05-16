<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::login');

$routes->get('/login', 'AuthController::login');
$routes->post('/loginUser', 'AuthController::loginUser');
$routes->get('/register', 'AuthController::register');
$routes->post('/registerUser', 'AuthController::registerUser');
$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->post('passwordreset/request', 'PasswordResetController::requestReset');
$routes->get('passwordreset/reset/(:any)', 'PasswordResetController::reset/$1');
$routes->post('passwordreset/update', 'PasswordResetController::updatePassword');
$routes->get('/primerpagina', 'AuthController::primerpag');
$routes->post('enviarmail', 'AuthController::enviarmail');

$routes->get('/cron/eliminar-no-verificados', 'AuthController::eliminarUsuariosNoVerificadosCron');
$routes->get('auth/verify-email/(:segment)', 'AuthController::verifyEmail/$1');


// Grupo de rutas protegidas (requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Rutas de autenticación protegidas
    $routes->get('/list-skates', 'AuthController::listSkates');
    $routes->get('/view-skate/(:any)', 'AuthController::viewSkate/$1');
    $routes->get('/logout', 'AuthController::logout');
    $routes->post('/add-skate', 'AuthController::addSkate');
    $routes->post('/unlink-skate/(:any)', 'AuthController::unlinkSkate/$1');
    $routes->get('skate/detail/(:num)', 'AuthController::detail/$1');
    $routes->post('update-skate-apodo', 'AuthController::updateSkateApodo');
    $routes->post('/deleteapodo/(:any)', 'AuthController::deleteapodo/$1');
    $routes->get('comprar', 'AuthController::comprar');
    $routes->get('nuevadireccion', 'AuthController::guardar');
    $routes->post('/direccion/guardarNueva', 'AuthController::guardarNueva');
    $routes->get('/obtener-localidades/(:num)', 'AuthController::obtenerLocalidadesPorProvincia/$1');
    $routes->post("paypal/createOrder", "PaypalController::createOrder");
    $routes->post("paypal/captureOrder", "PaypalController::captureOrder");
    $routes->get('/profile', 'AuthController::profile');
    $routes->post('/update-profile', 'AuthController::updateUserProfile');
    $routes->get('trayectoria', 'AuthController::trayectoria');
    $routes->get('profile/confirm-email/(:any)', 'AuthController::confirmEmail/$1');
    $routes->get('profile/confirm-password/(:any)', 'AuthController::confirmPassword/$1');
});

// Rutas del SkateController (si necesitan autenticación, deberías moverlas al grupo)
$routes->post('/skate/update', 'SkateController::updateSkateData');

