<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');

// AdminModel
$router->add('admin/ajouter');
$router->add('admin/index');
$router->add('admin/modifier/{id:\d+}', ['controller' => 'Admin', 'action' => 'modifier']);
$router->add('contact', ['controller' => 'AdminModel', 'action' => 'index']);

$router->dispatch($_SERVER['QUERY_STRING']);
