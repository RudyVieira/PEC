<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;
use App\Managers\UserManager;
use App\Entities\Intervention;
use App\Utils\LanguageLoader;

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
                null,
                $statut,
                (float)$data['tarif'],
                $user->getId(),
                (int)$data['idService'],
                null
            );

            $_SESSION['intervention'] = $intervention;

            return new Response('', 302, ['Location' => '/select-technician']);
        }

        $services = $this->getServices();
        $technicians = $this->getTechnicians();

        return $this->render('reserved', [
            'title' => 'Reserve an Intervention',
            'services' => $services,
            'technicians' => $technicians,
            'message' => $message,
        ]);
    }

    private function getServices() {
        $stmt = $this->db->query('SELECT id, nom, tarifMinimum FROM Service');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getTechnicians() {
        $stmt = $this->db->query('
            SELECT t.id, t.plageHoraireDebut, t.plageHoraireFin, u.nom, u.prenom
            FROM Technicien t
            JOIN Utilisateur u ON t.idUtilisateur = u.id
        ');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}