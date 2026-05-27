<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// DOTENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

define('SECRET_KEY', $_ENV['JWT_SECRET']);

$app = AppFactory::create();

// PARSEO DEL BODY
$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add( function ($request, $handler) {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, PATCH, DELETE')
        ->withHeader('Content-Type', 'application/json')
    ;
});


require __DIR__ . '/src/Config/db.php';
(require __DIR__ . '/src/Middlewares/AuthMiddleware.php');
(require __DIR__ . '/src/Routes/routes.php')($app);
(require __DIR__ . '/src/Controllers/AuthController.php');
(require __DIR__ . '/src/Controllers/UserController.php');
(require __DIR__ . '/src/Controllers/AssetController.php');
(require __DIR__ . '/src/Controllers/TradeController.php');
(require __DIR__ . '/src/Controllers/PortfolioController.php');
(require __DIR__ . '/src/Controllers/TransactionController.php');

$app->run();