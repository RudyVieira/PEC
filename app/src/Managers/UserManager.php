<?php

namespace App\Managers;

use App\Entities\User;
use PDO;

class UserManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch();
    }

    public function save(User $user) {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, telephone, motDePasse, validationMail, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $user->getTelephone(),
            $user->getMotDePasse(),
            (int)$user->getValidationMail(),
            $user->getRole()
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update(User $user) {
        $stmt = $this->pdo->prepare("UPDATE Utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?");
        $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $user->getTelephone(),
            $user->getId()
        ]);
    }
}