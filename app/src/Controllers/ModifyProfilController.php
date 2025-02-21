<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database\DatabaseConnexion;
use PDOException;

class ModifyProfilController extends AbstractController {

    public function process(Request $request): Response {
        session_start();
        if (!isset($_SESSION['user_email'])) {
            return new Response('', 302, ['Location' => '/login']);
        }

        try {
            $databaseConnexion = new DatabaseConnexion();
            $databaseConnexion->setConnexion();
            $pdo = $databaseConnexion->getConnexion();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $user = $userManager->findByEmail($_SESSION['user_email']);

        if (!$user) {
            return new Response('User not found', 404, ['Content-Type' => 'text/plain']);
        }

        if ($request->getMethod() === 'POST') {
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $email = $request->get('email');
            $telephone = $request->get('telephone');
            $adresseBureau = $request->get('adresseBureau');
            $codePostalBureau = $request->get('codePostalBureau');
            $villeBureau = $request->get('villeBureau');
            $adresseDomicile = $request->get('adresseDomicile');
            $codePostalDomicile = $request->get('codePostalDomicile');
            $villeDomicile = $request->get('villeDomicile');
            $marqueVehicule = $request->get('marqueVehicule');
            $modeleVehicule = $request->get('modeleVehicule');
            $immatriculationVehicule = $request->get('immatriculationVehicule');

            if (empty($nom) || empty($prenom) || empty($email) || empty($telephone)) {
                return new Response(json_encode(['message' => 'Tous les champs sont obligatoires.']), 400, ['Content-Type' => 'application/json']);
            }

            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);
            $user->setTelephone($telephone);
            $user->setAdresseBureau($adresseBureau);
            $user->setCodePostalBureau($codePostalBureau);
            $user->setVilleBureau($villeBureau);
            $user->setAdresseDomicile($adresseDomicile);
            $user->setCodePostalDomicile($codePostalDomicile);
            $user->setVilleDomicile($villeDomicile);

            $userManager->update($user);

            $userManager->updateVehicle($user->getId(), $marqueVehicule, $modeleVehicule, $immatriculationVehicule);

            $_SESSION['user_email'] = $email;

            return new Response('', 302, ['Location' => '/profil']);
        }

        $nom = $user->getNom();
        $prenom = $user->getPrenom();
        $email = $user->getEmail();
        $telephone = $user->getTelephone();
        $adresseBureau = $user->getAdresseBureau();
        $codePostalBureau = $user->getCodePostalBureau();
        $villeBureau = $user->getVilleBureau();
        $adresseDomicile = $user->getAdresseDomicile();
        $codePostalDomicile = $user->getCodePostalDomicile();
        $villeDomicile = $user->getVilleDomicile();
        $marqueVehicule = $user->getMarqueVehicule();
        $modeleVehicule = $user->getModeleVehicule();
        $immatriculationVehicule = $user->getImmatriculationVehicule();

        ob_start();
        include __DIR__ . '/../views/modify-profil.html';
        $content = ob_get_clean();

        return new Response($content, 200, ['Content-Type' => 'text/html']);
    }
}