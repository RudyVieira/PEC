<?php

namespace App\Managers;

use PDO;

class InterventionManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('
            SELECT i.*, s.nom AS service_nom, u1.nom AS technicien_nom, u2.nom AS utilisateur_nom
            FROM Intervention i
            JOIN Service s ON i.idService = s.id
            JOIN Technicien t ON i.idTechnicien = t.id
            JOIN Utilisateur u1 ON t.idUtilisateur = u1.id
            JOIN Utilisateur u2 ON i.idUtilisateur = u2.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserId(int $userId): array {
        $stmt = $this->pdo->prepare('
            SELECT i.*, s.nom AS service_nom, u1.nom AS technicien_nom, u2.nom AS utilisateur_nom
            FROM Intervention i
            JOIN Service s ON i.idService = s.id
            JOIN Technicien t ON i.idTechnicien = t.id
            JOIN Utilisateur u1 ON t.idUtilisateur = u1.id
            JOIN Utilisateur u2 ON i.idUtilisateur = u2.id
            WHERE i.idUtilisateur = ?
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByTechnicianId(int $technicianId): array {
        $stmt = $this->pdo->prepare('
            SELECT i.*, s.nom AS service_nom, u2.nom AS utilisateur_nom
            FROM Intervention i
            JOIN Service s ON i.idService = s.id
            JOIN Utilisateur u2 ON i.idUtilisateur = u2.id
            WHERE i.idTechnicien = ?
        ');
        $stmt->execute([$technicianId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}