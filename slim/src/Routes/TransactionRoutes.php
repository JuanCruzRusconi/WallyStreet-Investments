<?php

use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Controllers\TransactionController;

return function(App $app) {

    $app->get('/transactions', [TransactionController::class, 'getTransactions'])
        ->add(new AuthMiddleware());

};