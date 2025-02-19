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
        $userId = $this->pdo->lastInsertId();

        $this->saveAddress($userId, 'bureau', $user->getAdresseBureau(), $user->getCodePostalBureau(), $user->getVilleBureau());
        $this->saveAddress($userId, 'domicile', $user->getAdresseDomicile(), $user->getCodePostalDomicile(), $user->getVilleDomicile());

        return $userId;
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

        $this->updateAddress($user->getId(), 'bureau', $user->getAdresseBureau(), $user->getCodePostalBureau(), $user->getVilleBureau());
        $this->updateAddress($user->getId(), 'domicile', $user->getAdresseDomicile(), $user->getCodePostalDomicile(), $user->getVilleDomicile());
    }

    private function saveAddress($userId, $type, $adresse, $codePostal, $ville) {
        $stmt = $this->pdo->prepare("INSERT INTO Adresses (idUtilisateur, type, adresse, codePostal, ville) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $type, $adresse, $codePostal, $ville]);
    }

    private function updateAddress($userId, $type, $adresse, $codePostal, $ville) {
        $stmt = $this->pdo->prepare("UPDATE Adresses SET adresse = ?, codePostal = ?, ville = ? WHERE idUtilisateur = ? AND type = ?");
        $stmt->execute([$adresse, $codePostal, $ville, $userId, $type]);
    }
}