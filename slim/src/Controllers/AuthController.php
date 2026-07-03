<?php

namespace App\Controllers;
use PDO;
use App\Config\Database;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController {

    // LOGIN SESION
    public function postLogin(Request $request, Response $response) {
        
        $data = $request->getParsedBody();

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // VALIDAR INPUTS
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
            $response->getBody()->write(json_encode(["error" => "Campos incorrectos."]));
            return $response->withStatus(401);
        }

        // VALIDAR DB CREDENCIALES
        $pdo = Database::PDO();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user || !password_verify($password, $user['password'])) {
            $response->getBody()->write(json_encode(['error' => "Credenciales inválidas."]));
            return $response->withStatus(401);
        }

        // GENERAR TOKEN SESSION
        $token = bin2hex(random_bytes(32));

        $exp = date('Y-m-d H:i:s', time() + 300);

        $stmt = $pdo->prepare("UPDATE users SET token = ?, token_expired_at = ? WHERE id = ?");
        $stmt->execute([$token, $exp, $user['id']]);

        $response->getBody()->write(json_encode([
            "mensaje" => "Usuario logueado.",
            "usuario" => [
                "id" => $user['id'],
                "nombre" => $user['name'],
                "email" => $user['email'],
                "admin" => $user['is_admin']
            ],
            "token" => $token,
            "expira" => $exp
        ]));
        return $response->withStatus(200);

    }

    // LOGOUT SESION
    public function postLogout(Request $request, Response $response) {
        
        $user = $request->getAttribute('user');

        if(!$user) {
            $response->getbody()->write(json_encode(["error" => "No existe usuario autenticado."]));
            return $response->withStatus(401);
        }

        $pdo = Database::PDO();

        $stmt = $pdo->prepare("UPDATE users SET token = NULL, token_expired_at = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);

        $response->getBody()->write(json_encode(["mensaje" => "Token eliminado. Sesión cerrada."]));
        return $response->withStatus(200);

    }
}