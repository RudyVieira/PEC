<?php

namespace App\Entities;

use App\Lib\Entities\AbstractEntity;

class Intervention extends AbstractEntity {
    public ?int $id;
    public string $dateDemande;
    public string $lieuIntervention;
    public string $horaireDebut;
    public string $horaireFin;
    public int $statut;
    public float $tarif;
    public int $idUtilisateur;
    public int $idService;
    public ?int $idTechnicien; // Allow idTechnicien to be nullable

    public function __construct(
        ?int $id,
        string $dateDemande,
        string $lieuIntervention,
        string $horaireDebut,
        string $horaireFin,
        int $statut,
        float $tarif,
        int $idUtilisateur,
        int $idService,
        ?int $idTechnicien // Allow idTechnicien to be nullable
    ) {
        $this->id = $id;
        $this->dateDemande = $dateDemande;
        $this->lieuIntervention = $lieuIntervention;
        $this->horaireDebut = $horaireDebut;
        $this->horaireFin = $horaireFin;
        $this->statut = $statut;
        $this->tarif = $tarif;
        $this->idUtilisateur = $idUtilisateur;
        $this->idService = $idService;
        $this->idTechnicien = $idTechnicien;
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