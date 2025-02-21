<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;

class SelectTechnicianController extends AbstractController {
    private $db;

    public function __construct() {
        $this->db = (new DatabaseConnexion())->setConnexion()->getConnexion();
    }

    public function process(Request $request): Response {
        session_start();
        if (!isset($_SESSION['user_email']) || !isset($_SESSION['intervention'])) {
            return new Response('', 302, ['Location' => '/login']);
        }

        $intervention = $_SESSION['intervention'];
        $technicians = $this->getAvailableTechnicians($intervention->getHoraireDebut(), $intervention->getHoraireFin());

        return $this->render('select-technician', [
            'title' => 'Select a Technician',
            'technicians' => $technicians,
            'intervention' => $intervention
        ]);
    }

    private function getAvailableTechnicians($horaireDebut) {
        $stmt = $this->db->prepare('
            SELECT t.*, u.nom, u.prenom FROM Technicien t
            JOIN Utilisateur u ON t.idUtilisateur = u.id
            WHERE t.plageHoraireDebut <= ? AND t.plageHoraireFin >= ?
        ');
        $stmt->execute([$horaireDebut, $horaireDebut]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}