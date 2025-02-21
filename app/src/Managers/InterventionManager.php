<?php

namespace App\Managers;

use PDO;

class InterventionManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByUserId(int $userId): array {
        $stmt = $this->pdo->prepare('
            SELECT i.*, s.nom AS service_nom, u.nom AS technicien_nom, u.prenom AS technicien_prenom
            FROM Intervention i
            LEFT JOIN Service s ON i.idService = s.id
            LEFT JOIN Technicien t ON i.idTechnicien = t.id
            LEFT JOIN Utilisateur u ON t.idUtilisateur = u.id
            WHERE i.idUtilisateur = ?
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}