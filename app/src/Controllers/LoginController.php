<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database\DatabaseConnexion; 
use PDOException;

class LoginController extends AbstractController {

    public function process(Request $request): Response {
        if ($request->getMethod() === 'GET') {
            return new Response(file_get_contents(__DIR__ . '/../views/login.html'), 200, ['Content-Type' => 'text/html']);
        }

        try {
            $databaseConnexion = new DatabaseConnexion();
            $databaseConnexion->setConnexion();
            $pdo = $databaseConnexion->getConnexion();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $email = $request->get('email');
        $motDePasse = $request->get('motDePasse');

        $user = $userManager->findByEmail($email);

        if (!$user) {
            $errors['user'] = 'Adresse mail incorrecte';
            ob_start();
            include __DIR__ . '/../views/login.html';
            $content = ob_get_clean();
            return new Response($content, 400, ['Content-Type' => 'text/html']);
        }

        if (!password_verify($motDePasse, $user->getMotDePasse())) {
            $errors['password'] = 'Mot de passe incorrect';
            ob_start();
            include __DIR__ . '/../views/login.html';
            $content = ob_get_clean();
            return new Response($content, 400, ['Content-Type' => 'text/html']);
        }

        session_start();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole();

        if ($user->getRole() === 'admin') {
            return new Response('', 302, ['Location' => '/admin']);
        }

        return new Response('', 302, ['Location' => '/profil']);
    }
}