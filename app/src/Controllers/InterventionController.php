<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\InterventionManager;
use PDO;

class InterventionController extends AbstractController {

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

        $interventionManager = new InterventionManager($pdo);

        $data = $request->getBody();
        $interventionId = (int)$data['interventionId'];
        $status = (int)$data['status'];

        $success = $interventionManager->updateInterventionStatus($interventionId, $status);

        if ($success) {
            return new Response('', 302, ['Location' => '/profil-technicien']);
        } else {
            return new Response('Failed to update intervention status', 500, ['Content-Type' => 'text/plain']);
        }
    }
}