<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use PDOException;

class DashboardController extends AbstractController {

    public function process(Request $request): Response {
        ob_start();
        include __DIR__ . '/../Views/dashboard.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}

// modifier le Controller quand on aura les données 
// ne pas oublier de changer la route dans le fichier routes.php