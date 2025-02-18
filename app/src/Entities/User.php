<?php

namespace App\Entities;

class User {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $motDePasse;
    private $validationMail;
    private $role;

    // Getters et setters pour chaque propriété
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getMotDePasse() {
        return $this->motDePasse;
    }

    public function setMotDePasse($motDePasse) {
        $this->motDePasse = $motDePasse;
    }

    public function getValidationMail() {
        return $this->validationMail;
    }

    public function setValidationMail($validationMail) {
        $this->validationMail = (int)$validationMail; // Cast to integer
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}