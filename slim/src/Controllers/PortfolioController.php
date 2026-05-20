<?php

namespace App\Controllers;
use PDO;
use App\Config\Database;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PortfolioController {

    // GET PORTFOLIO
    public function getPortfolio(Request $request, Response $response) {

        $user = $request->getAttribute('user');

        $pdo = Database::PDO();

        $stmt = $pdo->prepare("SELECT
            portfolio.asset_id,
            assets.name,
            assets.current_price,
            portfolio.quantity
        FROM portfolio
        JOIN assets
            ON portfolio.asset_id = assets.id
        WHERE portfolio.user_id = ?");
        $stmt->execute([$user['id']]);

        $portfolio = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // VALOR TOTAL
        foreach($portfolio as &$asset) {
            $asset['total_value'] = round($asset['quantity'] * $asset['current_price'], 2);
        }

        $response->getBody()->write(json_encode([
            "mensaje" => "Listado de activos del portfolio",
            "activos" => $portfolio
        ]));
        return $response->withStatus(200);

    }

    public function deletePortfolio(Request $request, Response $response, $args) {
        
        $user = $request->getAttribute('user');

        $assetId = $args['asset_id'];

        if(!is_numeric($assetId)) {
            $response->getBody()->write(json_encode(["error" => "ID de activo invalido."]));
            return $response->withStatus(400);
        }

        $pdo = Database::PDO();

        // BUSCAR EN PORTFOLIO
        $stmt = $pdo->prepare("SELECT * FROM portfolio where user_id = ? AND asset_id = ?");
        $stmt->execute([$user['id'], $assetId]);

        $portfolio = $stmt->fetch(PDO::FETCH_ASSOC);

        // VALIDAR PORTFOLIO
        if(!$portfolio) {
            $response->getBody()->write(json_encode(["error" => "No se encuentra este activo en el portfolio."]));
            return $response->withStatus(404);
        }

        // VALIDACION CRITICA DE ACTIVOS PRE ELIMINAR
        if($portfolio['quantity'] > 0) {
            $response->getBody()->write(json_encode(["error" => "No puedes quitar un activo de tu portfolio si aún tienes unidades. Debes venderlas primero."]));
            return $response->withStatus(409);
        }

        // ELIMINAR
        $stmt = $pdo->prepare("DELETE FROM portfolio WHERE user_id = ? AND asset_id = ?");
        $stmt->execute([$user['id'], $assetId]);

        $response->getBody()->write(json_encode([
            "mensaje" => "Activo eliminado correctamente del portfolio"]));
        return $response->withStatus(200);

    }

}