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

    public function findRecentByUserId(int $userId, int $limit): array {
        $stmt = $this->pdo->prepare('
            SELECT i.*, s.nom AS service_nom, u.nom AS technicien_nom, u.prenom AS technicien_prenom
            FROM Intervention i
            LEFT JOIN Service s ON i.idService = s.id
            LEFT JOIN Technicien t ON i.idTechnicien = t.id
            LEFT JOIN Utilisateur u ON t.idUtilisateur = u.id
            WHERE i.idUtilisateur = :userId
            ORDER BY i.dateDemande DESC
            LIMIT :limit
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByTechnicianId(int $technicianId): array {
        $stmt = $this->pdo->prepare('
            SELECT i.*, s.nom AS service_nom, u.nom AS utilisateur_nom, u.prenom AS utilisateur_prenom
            FROM Intervention i
            LEFT JOIN Service s ON i.idService = s.id
            LEFT JOIN Utilisateur u ON i.idUtilisateur = u.id
            WHERE i.idTechnicien = ?
        ');
        $stmt->execute([$technicianId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateInterventionStatus(int $interventionId, int $status): bool {
        $stmt = $this->pdo->prepare('UPDATE Intervention SET statut = ? WHERE id = ?');
        return $stmt->execute([$status, $interventionId]);
    }
}