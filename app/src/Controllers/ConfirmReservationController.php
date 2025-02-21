<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;
use App\Entities\Intervention;
use App\Utils\LanguageLoader;

class ConfirmReservationController extends AbstractController {
    private $db;

    public function __construct() {
        $this->db = (new DatabaseConnexion())->setConnexion()->getConnexion();
    }

    public function process(Request $request): Response {
        session_start();
        $this->initialize($request);
        if (!isset($_SESSION['user_email']) || !isset($_SESSION['intervention'])) {
            return new Response('', 302, ['Location' => '/login']);
        }

        $data = $request->getBody();
        $intervention = $_SESSION['intervention'];
        $intervention->setIdTechnicien((int)$data['idTechnicien']);

        $this->saveIntervention($intervention);
        unset($_SESSION['intervention']);

        return new Response('', 302, ['Location' => '/profil']);
    }

    private function saveIntervention(Intervention $intervention) {
        $stmt = $this->db->prepare('INSERT INTO Intervention (dateDemande, lieuIntervention, horaire_debut, horaire_fin, statut, tarif, idUtilisateur, idService, idTechnicien) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $intervention->getDateDemande(),
            $intervention->getLieuIntervention(),
            $intervention->getHoraireDebut(),
            $intervention->getHoraireFin(),
            $intervention->getStatut(),
            $intervention->getTarif(),
            $intervention->getIdUtilisateur(),
            $intervention->getIdService(),
            $intervention->getIdTechnicien()
        ]);
    }
}