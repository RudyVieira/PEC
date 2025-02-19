<?php

namespace App\Entities;

class User {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $telephone;
    private $motDePasse;
    private $validationMail;
    private $role;
    private $adresseBureau;
    private $codePostalBureau;
    private $villeBureau;
    private $adresseDomicile;
    private $codePostalDomicile;
    private $villeDomicile;

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

    public function getTelephone() {
        return $this->telephone;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
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
        $this->validationMail = (int)$validationMail;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getAdresseBureau() {
        return $this->adresseBureau;
    }

    public function setAdresseBureau($adresseBureau) {
        $this->adresseBureau = $adresseBureau;
    }

    public function getCodePostalBureau() {
        return $this->codePostalBureau;
    }

    public function setCodePostalBureau($codePostalBureau) {
        $this->codePostalBureau = $codePostalBureau;
    }

    public function getVilleBureau() {
        return $this->villeBureau;
    }

    public function setVilleBureau($villeBureau) {
        $this->villeBureau = $villeBureau;
    }

    public function getAdresseDomicile() {
        return $this->adresseDomicile;
    }

    public function setAdresseDomicile($adresseDomicile) {
        $this->adresseDomicile = $adresseDomicile;
    }

    public function getCodePostalDomicile() {
        return $this->codePostalDomicile;
    }

    public function setCodePostalDomicile($codePostalDomicile) {
        $this->codePostalDomicile = $codePostalDomicile;
    }

    public function getVilleDomicile() {
        return $this->villeDomicile;
    }

    public function setVilleDomicile($villeDomicile) {
        $this->villeDomicile = $villeDomicile;
    }
}