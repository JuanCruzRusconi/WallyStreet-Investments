<?php

use Slim\App;

return function(App $app) {

    // RUTAS AUTENTICACION
    (require __DIR__ . '/AuthRoutes.php')($app);

    // RUTAS USUARIOS
    (require __DIR__ . '/UserRoutes.php')($app);

    // RUTAS ACTIVOS
    (require __DIR__ . '/AssetRoutes.php')($app);
    
    // RUTAS OPERACIONES
    (require __DIR__ . '/TradeRoutes.php')($app);

    // RUTAS PORTFOLIO
    (require __DIR__ . '/PortfolioRoutes.php')($app);

    // RUTAS TRANSACCIONES
    (require __DIR__ . '/TransactionRoutes.php')($app);

};