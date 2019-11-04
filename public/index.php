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
$router->add('admin/ajouter', ['controller' => 'Admin', 'action' => 'ajouter']);
$router->add('admin/connexion', ['controller' => 'Admin', 'action' => 'connexion']);
$router->add('admin/index');
$router->add('admin/modifier/{id:\d+}', ['controller' => 'Admin', 'action' => 'modifier']);
$router->add('admin/deconnexion', ['controller' => 'Admin', 'action' => 'deconnexion']);
$router->add('contact', ['controller' => 'Client', 'action' => 'contact']);

// Client
$router->add('client/liste', ['controller' => 'Client', 'action' => 'index']);
$router->add('client/ajouter', ['controller' => 'Client', 'action' => 'ajouter']);
$router->add('client/connexion', ['controller' => 'Client', 'action' => 'connexion']);
$router->add('client/deconnexion', ['controller' => 'Client', 'action' => 'deconnexion']);

// Support
$router->add('support/{id:\d+}', ['controller' => 'Support', 'action' => 'support']);
$router->add('admin/support/{id:\d+}', ['controller' => 'Support', 'action' => 'supportAdmin']);
$router->add('support/processMessage', ['controller' => 'Support', 'action' => 'processMessage']);
$router->add('support/newMessages', ['controller' => 'Support', 'action' => 'newMessages']);


$router->dispatch($_SERVER['QUERY_STRING']);
