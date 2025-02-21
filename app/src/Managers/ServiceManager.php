<?php

namespace App\Managers;

use PDO;

class ServiceManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM Service');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}