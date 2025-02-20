<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;
use App\Managers\UserManager;
use App\Entities\Intervention;

class ReservedController extends AbstractController {
    private $db;

    public function __construct() {
        $this->db = (new DatabaseConnexion())->setConnexion()->getConnexion();
    }

    public function process(Request $request): Response {
        session_start();
        if (!isset($_SESSION['user_email'])) {
            return new Response('', 302, ['Location' => '/login']);
        }

        $message = '';

        if ($request->getMethod() === 'POST') {
            $data = $request->getBody();
            $statut = isset($data['statut']) && $data['statut'] === 'on' ? 1 : 0;
            $userManager = new UserManager($this->db);
            $user = $userManager->findByEmail($_SESSION['user_email']);
            $intervention = new Intervention(
                null,
                $data['dateDemande'],
                $data['lieuIntervention'],
                $data['horaireDebut'],
                $data['horaireFin'],
                $statut,
                (float)$data['tarif'],
                $user->getId(),
                (int)$data['idService'],
                null // Technicien will be selected in the next step
            );

            $_SESSION['intervention'] = $intervention;

            return new Response('', 302, ['Location' => '/select-technician']);
        }

        $services = $this->getServices();

        return $this->render('reserved', [
            'title' => 'Reserve an Intervention',
            'services' => $services,
            'message' => $message,
        ]);
    }

    private function getServices() {
        $stmt = $this->db->query('SELECT id, nom, tarifMinimum FROM Service');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}