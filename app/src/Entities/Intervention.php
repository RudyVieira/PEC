<?php

namespace App\Entities;

use App\Lib\Entities\AbstractEntity;

class Intervention extends AbstractEntity {
    public ?int $id = null;
    public string $dateDemande = '';
    public string $lieuIntervention = '';
    public string $horaireDebut = '';
    public string $horaireFin = '';
    public int $statut = 0;
    public float $tarif = 0.0;
    public int $idUtilisateur = 0;
    public int $idService = 0;
    public ?int $idTechnicien = null;

    public function __construct() {
        // Constructeur sans arguments pour PDO
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getDateDemande(): string {
        return $this->dateDemande;
    }

    public function getLieuIntervention(): string {
        return $this->lieuIntervention;
    }

    public function getHoraireDebut(): string {
        return $this->horaireDebut;
    }

    public function getHoraireFin(): string {
        return $this->horaireFin;
    }

    public function getStatut(): int {
        return $this->statut;
    }

    public function getTarif(): float {
        return $this->tarif;
    }

    public function getIdUtilisateur(): int {
        return $this->idUtilisateur;
    }

    public function getIdService(): int {
        return $this->idService;
    }

    public function getIdTechnicien(): ?int {
        return $this->idTechnicien;
    }

    public function setId(?int $id) {
        $this->id = $id;
    }

    public function setDateDemande(string $dateDemande) {
        $this->dateDemande = $dateDemande;
    }

    public function setLieuIntervention(string $lieuIntervention) {
        $this->lieuIntervention = $lieuIntervention;
    }

    public function setHoraireDebut(string $horaireDebut) {
        $this->horaireDebut = $horaireDebut;
    }

    public function setHoraireFin(string $horaireFin) {
        $this->horaireFin = $horaireFin;
    }

    public function setStatut(int $statut) {
        $this->statut = $statut;
    }

    public function setTarif(float $tarif) {
        $this->tarif = $tarif;
    }

    public function setIdUtilisateur(int $idUtilisateur) {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function setIdService(int $idService) {
        $this->idService = $idService;
    }

    public function setIdTechnicien(?int $idTechnicien) {
        $this->idTechnicien = $idTechnicien;
    }
}