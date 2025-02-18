<?php

namespace App\Managers;

use App\Entities\User;
use PDO;

class UserManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function save(User $user) {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, motDePasse, validationMail, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $user->getMotDePasse(),
            (int)$user->getValidationMail(),
            $user->getRole()
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update(User $user) {
        $stmt = $this->pdo->prepare("UPDATE Utilisateur SET validationMail = ? WHERE email = ?");
        $stmt->execute([
            (int)$user->getValidationMail(),
            $user->getEmail()
        ]);
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $user = new User();
            $user->setId($data['id']);
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setEmail($data['email']);
            $user->setMotDePasse($data['motDePasse']);
            $user->setValidationMail($data['validationMail']);
            $user->setRole($data['role']);
            return $user;
        }
        return null;
    }
}