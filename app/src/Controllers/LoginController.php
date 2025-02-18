<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database;
use PDOException;

class LoginController extends AbstractController {

    public function process(Request $request): Response {
        if ($request->getMethod() === 'GET') {
            return new Response(file_get_contents(__DIR__ . '/../Views/login.html'), 200, ['Content-Type' => 'text/html']);
        }

        try {
            $pdo = Database::getConnection();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $email = $request->get('email');
        $motDePasse = $request->get('motDePasse');

        $user = $userManager->findByEmail($email);

        if (!$user || !password_verify($motDePasse, $user->getMotDePasse())) {
            return new Response(json_encode(['message' => 'Invalid email or password']), 400, ['Content-Type' => 'application/json']);
        }

        session_start();
        $_SESSION['user_email'] = $user->getEmail();

        return new Response('', 302, ['Location' => '/profil']);
    }
}