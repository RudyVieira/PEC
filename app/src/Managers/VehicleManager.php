<?php

namespace App\Managers;

use PDO;

class VehicleManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('
            SELECT v.*, u.nom AS utilisateur_nom
            FROM Vehicule v
            JOIN Utilisateur u ON v.idUtilisateur = u.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}