# Projet PEC

## Description
Doc 2 Wheels est une société spécialisée dans le service de réparation de
deux-roues (motos, scooters) à domicile, au bureau ou dans la rue. L'objectif du
projet est de développer une application qui facilitera la vie des motards et des
scootéristes dans les mégapoles. Le service répondra à des problématiques telles
que la pénurie de disponibilité des ateliers officiels, des coûts de main-d'œuvre
élevés et la nécessité d'une prise en charge rapide des urgences.


## Prérequis

- PHP 7.4 ou supérieur
- Composer
- MySQL
- Docker (optionnel)


# Installation

### Cloner le projet
```
git clone https://github.com/<your-username>/php-oop-exercice
```

### Installer les dépendances
```
cd app
composer install
```

#### PHPMailer
```
docker exec -it php-framework bash
composer require phpmailer/phpmailer
```

#### SASS
à la racine du projet
```
npm install --save-dev sass
```

### Démarrer Docker
```
docker compose up --build
```

### Utilisation
Accédez à l'application dans votre navigateur à l'adresse suivante :
```
http://localhost:8080/login
```

## Contributeurs
- Noah OEU (https://github.com/SenkaX) - Back
- Rudy VIEIRA (https://github.com/RudyVieira) - Front
- Morgane DASSONVILLE (https://github.com/Jun080) - Création des diagrammes et Rédaction du cahier des charges
