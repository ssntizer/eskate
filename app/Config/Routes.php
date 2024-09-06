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
$routes->get('/list-skates', 'AuthController::listSkates');
$routes->get('/view-skate/(:any)', 'AuthController::viewSkate/$1');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/add-skate', 'AuthController::addSkate');
