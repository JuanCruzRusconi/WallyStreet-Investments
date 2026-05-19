<?php

use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Controllers\PortfolioController;

return function(App $app) {

    $app->get('/portfolio', [PortfolioController::class, 'getPortfolio'])
        ->add(new AuthMiddleware());

    $app->delete('/portfolio/{asset_id}', [PortfolioController::class, 'deletePortfolio'])
        ->add(new AuthMiddleware());

};