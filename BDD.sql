CREATE TABLE `Utilisateur` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(50) NOT NULL,
  `prenom` VARCHAR(50) NOT NULL,
  `motDePasse` VARCHAR(60) NOT NULL,
  `email` VARCHAR(320) UNIQUE NOT NULL,
  `validationMail` BOOLEAN,
  `telephone` VARCHAR(10),
  `role` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `Utilisateur` (`nom`, `prenom`, `motDePasse`, `email`, `validationMail`, `telephone`, `role`) VALUES
('AdminNom', 'Admin', 'mdpadmin', 'admin@example.com', true, '0123456789', 'admin'),
('TechnicienNom', 'Tech', 'mdptech', 'technicien@example.com', true, '0123456789', 'technicien'),
('UtilisateurNom', 'User', 'mdptech', 'utilisateur@example.com', true, '0123456789', 'utilisateur');



CREATE TABLE `Adresses` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `idUtilisateur` INTEGER NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `adresse` VARCHAR(255),
  `codePostal` VARCHAR(10),
  `ville` VARCHAR(50),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
);

CREATE TABLE `Service` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `duree` INTEGER NOT NULL,
  `tarifMinimum` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `Service` (`nom`, `description`, `duree`, `tarifMinimum`) VALUES
("Réparation", "Service de réparation pour motos et scooters", 60, 50.00),
("Entretien", "Service d'entretien régulier pour motos et scooters", 90, 75.00),
("Dépannage d'urgence", "Service de dépannage d'urgence pour motos et scooters", 120, 100.00);



CREATE TABLE `Type` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `typeDeService` VARCHAR(50) NOT NULL,
  `idService` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `Vehicule` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `marque` VARCHAR(50) NOT NULL,
  `modele` VARCHAR(50) NOT NULL,
  `immatriculation` VARCHAR(60) NOT NULL,
  `idUtilisateur` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `Avis` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `commentaire` TEXT NOT NULL,
  `note` INTEGER NOT NULL,
  `date` DATE NOT NULL,
  `idUtilisateur` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `Technicien` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `plageHoraireDebut` TIME,
  `plageHoraireFin` TIME,
  `idUtilisateur` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `Technicien` (`plageHoraireDebut`, `plageHoraireFin`, `idUtilisateur`) VALUES
('08:00:00', '17:00:00', 1),
('09:00:00', '18:00:00', 2);



CREATE TABLE `Specialite` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `UtilisateurSpecialite` (
  `idUtilisateur` INTEGER NOT NULL,
  `idSpecialite` INTEGER NOT NULL,
  PRIMARY KEY (`idUtilisateur`, `idSpecialite`)
);

CREATE TABLE `Intervention` (
  `id` INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  `dateDemande` DATE NOT NULL,
  `lieuIntervention` VARCHAR(255) NOT NULL,
  `horaire_debut` TIME NOT NULL,
  `horaire_fin` TIME NOT NULL,
  `statut` BOOLEAN,
  `tarif` DECIMAL(10,2) NOT NULL,
  `idUtilisateur` INTEGER NOT NULL,
  `idService` INTEGER NOT NULL,
  `idTechnicien` INTEGER,
  PRIMARY KEY (`id`)
);

CREATE INDEX `utilisateur_index_email` ON `Utilisateur` (`email`);

ALTER TABLE `Type` ADD FOREIGN KEY (`idService`) REFERENCES `Service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Vehicule` ADD FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Avis` ADD FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Technicien` ADD FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `UtilisateurSpecialite` ADD FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `UtilisateurSpecialite` ADD FOREIGN KEY (`idSpecialite`) REFERENCES `Specialite` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Intervention` ADD FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Intervention` ADD FOREIGN KEY (`idService`) REFERENCES `Service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Intervention` ADD FOREIGN KEY (`idTechnicien`) REFERENCES `Technicien` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
