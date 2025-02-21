<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Managers\InterventionManager;
use PDO;

class ProfilController extends AbstractController {

    public function process(Request $request): Response {
        session_start();
        if (!isset($_SESSION['user_email'])) {
            return new Response('', 302, ['Location' => '/login']);
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
        $user = $userManager->findByEmail($_SESSION['user_email']);

        if (!$user) {
            return new Response('User not found', 404, ['Content-Type' => 'text/plain']);
        }

        $interventionManager = new InterventionManager($pdo);
        $interventions = $interventionManager->findByUserId($user->getId());

        ob_start();
        include __DIR__ . '/../views/profile.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}