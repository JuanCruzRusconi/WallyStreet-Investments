<?php

namespace App\Controllers;
use PDO;
use App\Config\Database;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TransactionController {

    public function getTransactions(Request $request, Response $response) {
        
        $user = $request->getAttribute('user');

        $params = $request->getQueryParams();

        $type = $params['type'] ?? null;
        $assetId = $params['asset_id'] ?? null;

        $pdo = Database::PDO();

        $sql = "SELECT id, asset_id, transaction_type, quantity, price_per_unit, total_amount, transaction_date 
                FROM transactions 
                WHERE user_id = ?";

        $values = [$user['id']];

        // FILTRO TYPE
        if($type !== null) {

            if($type !== 'buy' && $type !== 'sell') {
                $response->getBody()->write(json_encode([
                    "error" => "Tipo de transacción inválido."
                ]));

                return $response->withStatus(400);
            }

            $sql .= " AND transaction_type = ?";
            $values[] = $type;
        }

        // FILTRO ASSET_ID
        if($assetId !== null) {

            if(!is_numeric($assetId)) {
                $response->getBody()->write(json_encode([
                    "error" => "ID de activo inválido."
                ]));

                return $response->withStatus(400);
            }

            $sql .= " AND asset_id = ?";
            $values[] = $assetId;
        }

        $sql .= " ORDER BY transaction_date DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode([
            "mensaje" => "Lista de transacciones.",
            "transacciones" => $transactions
        ]));
        return $response->withStatus(200);

    }
    
}