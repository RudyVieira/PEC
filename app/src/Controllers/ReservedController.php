<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;
use App\Entities\Intervention;

class ReservedController extends AbstractController {
    private $db;

    public function __construct() {
        $this->db = (new DatabaseConnexion())->setConnexion()->getConnexion();
    }

    public function process(Request $request): Response {
        $message = '';

        if ($request->getMethod() === 'POST') {
            $data = $request->getBody();
            $statut = isset($data['statut']) && $data['statut'] === 'on' ? 1 : 0;
            $intervention = new Intervention(
                null,
                $data['dateDemande'],
                $data['lieuIntervention'],
                $data['horaireDebut'],
                $data['horaireFin'],
                $statut,
                (float)$data['tarif'],
                (int)$data['idUtilisateur'],
                (int)$data['idService'],
                (int)$data['idTechnicien']
            );

            $this->saveIntervention($intervention);
            $message = 'Votre réservation est faite';
        }

        $services = $this->getServices();
        $technicians = $this->getAvailableTechnicians();

        return $this->render('reserved', [
            'title' => 'Reserve an Intervention',
            'services' => $services,
            'technicians' => $technicians,
            'message' => $message,
        ]);
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

    private function getServices() {
        $stmt = $this->db->query('SELECT id, nom, tarifMinimum FROM Service');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getAvailableTechnicians() {
        $stmt = $this->db->prepare('
            SELECT t.*, u.nom, u.prenom, t.plageHoraireDebut, t.plageHoraireFin FROM Technicien t
            JOIN Utilisateur u ON t.idUtilisateur = u.id
        ');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}