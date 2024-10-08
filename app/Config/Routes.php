<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::primerpag');
$routes->get('/login', 'AuthController::login');
$routes->post('/loginUser', 'AuthController::loginUser');
$routes->get('/register', 'AuthController::register');
$routes->post('/registerUser', 'AuthController::registerUser');
$routes->get('/list-skates', 'AuthController::listSkates');
$routes->get('/view-skate/(:any)', 'AuthController::viewSkate/$1');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/add-skate', 'AuthController::addSkate');
$routes->post('/unlink-skate/(:any)', 'AuthController::unlinkSkate/$1');
$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->post('passwordreset/request', 'PasswordResetController::requestReset');
$routes->get('passwordreset/reset/(:any)', 'PasswordResetController::reset/$1');
$routes->post('passwordreset/update', 'PasswordResetController::updatePassword');
$routes->post('/skate/update', 'SkateController::updateSkateData');
$routes->get('/login', 'AuthController::login');
$routes->post('skate/activate-buzzer/(:any)', 'SkateController::activateBuzzerTimed/$1');