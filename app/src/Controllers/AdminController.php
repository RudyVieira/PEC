<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Managers\InterventionManager;
use App\Managers\ServiceManager;
use App\Managers\VehicleManager;
use PDO;

class AdminController extends AbstractController {

    public function process(Request $request): Response {
        session_start();
        if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
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
        $interventionManager = new InterventionManager($pdo);
        $serviceManager = new ServiceManager($pdo);
        $vehicleManager = new VehicleManager($pdo);

        $users = $userManager->findAll();
        $interventions = $interventionManager->findAll();
        $services = $serviceManager->findAll();
        $vehicles = $vehicleManager->findAll();

        $ongoingInterventions = $interventionManager->countByStatus(0);
        $totalTechnicians = $userManager->countByRole('technicien');

        ob_start();
        include __DIR__ . '/../views/admin.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}