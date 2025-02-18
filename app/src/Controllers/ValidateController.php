<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database;
use PDO;

class ValidateController extends AbstractController {

    public function process(Request $request): Response {
        $email = $request->get('email');

        if (!$email) {
            return new Response('Email non fourni', 400, ['Content-Type' => 'text/plain']);
        }

        $config = json_decode(file_get_contents(__DIR__ . '/../../config/database.json'), true);

        $host = $config['host'];
        $user = $config['user'];
        $password = $config['password'];
        $database = $config['database'];
        $port = $config['port'];

        $dsn = "mysql:host=$host;dbname=$database;port=$port";
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userManager = new UserManager($pdo);
        $user = $userManager->findByEmail($email);

        if (!$user) {
            return new Response('Utilisateur non trouvé', 404, ['Content-Type' => 'text/plain']);
        }

        $user->setValidationMail(true);
        $userManager->save($user);

        ob_start();
        include __DIR__ . '/../Views/validateEmail.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}