<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database\DatabaseConnexion; 
use PDOException;

class ValidateController extends AbstractController {

    public function process(Request $request): Response {
        try {
            $databaseConnexion = new DatabaseConnexion();
            $databaseConnexion->setConnexion();
            $pdo = $databaseConnexion->getConnexion();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $email = $request->get('email');

        $user = $userManager->findByEmail($email);
        if (!$user) {
            return new Response(json_encode(['message' => 'Utilisateur non trouvé.']), 404, ['Content-Type' => 'application/json']);
        }

        $user->setValidationMail(true);
        $userManager->update($user);

        ob_start();
        include __DIR__ . '/../views/validate-email.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}