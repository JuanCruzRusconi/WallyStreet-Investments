<?php

namespace App\Middlewares;
use PDO;
use App\Config\Database;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

// JWT
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function __invoke(Request $request, $handler) {
        $authHeader = $request->getHeaderLine('Authorization');

        if(!$authHeader) {
            $response = new Response();
            $response->getBody()->write(json_encode(["error" => "Se requiere el token para la petición."]));
            return $response->withStatus(401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        $pdo = Database::PDO();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ?");
        $stmt->execute([$token]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user) {
            $response = new Response();
            $response->getBody()->write(json_encode(["error" => "El token es inválido."]));
            return $response->withStatus(401);
        }

        // VALIDAR EXPIRACIÓN
        if($user['token_expired_at'] < date('Y-m-d H:i:s')) {
            $response = new Response();
            $response->getBody()->write(json_encode(["error" => "El token ha expirado."]));
            return $response->withStatus(401);
        }

        // EXTENSION DE SESION DE USUARIO
        $stmt = $pdo->prepare("UPDATE users SET token_expired_at = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE id = ?");
        $stmt->execute([$user['id']]);

        // GUARDAR USER EN REQUEST
        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
};