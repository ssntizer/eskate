<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::welcome');
$routes->get('/login', 'AuthController::login');
$routes->post('/loginUser', 'AuthController::loginUser');
$routes->get('/register', 'AuthController::register');
$routes->post('/registerUser', 'AuthController::registerUser');
$routes->get('/welcome', 'AuthController::welcome');
$routes->get('/logout', 'AuthController::logout');