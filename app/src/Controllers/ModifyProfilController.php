<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database;
use PDOException;

class ModifyProfilController extends AbstractController {

    public function process(Request $request): Response {
        session_start();
        if (!isset($_SESSION['user_email'])) {
            return new Response('', 302, ['Location' => '/login']);
        }

        try {
            $pdo = Database::getConnection();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $user = $userManager->findByEmail($_SESSION['user_email']);

        if (!$user) {
            return new Response('User not found', 404, ['Content-Type' => 'text/plain']);
        }

        if ($request->getMethod() === 'POST') {
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $email = $request->get('email');
            $telephone = $request->get('telephone');

            if (empty($nom) || empty($prenom) || empty($email) || empty($telephone)) {
                return new Response(json_encode(['message' => 'Tous les champs sont obligatoires.']), 400, ['Content-Type' => 'application/json']);
            }

            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);
            $user->setTelephone($telephone);

            $userManager->update($user);

            $_SESSION['user_email'] = $email;

            return new Response('', 302, ['Location' => '/profil']);
        }

        $nom = $user->getNom();
        $prenom = $user->getPrenom();
        $email = $user->getEmail();
        $telephone = $user->getTelephone();

        ob_start();
        include __DIR__ . '/../Views/modify-profil.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}