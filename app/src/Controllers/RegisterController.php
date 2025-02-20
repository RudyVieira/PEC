<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use App\Entities\User;
use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database\DatabaseConnexion; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PDOException;

class RegisterController extends AbstractController {

    public function process(Request $request): Response {
        if ($request->getMethod() === 'GET') {
            $view = $request->getPath() === '/register-technicien' ? 'register-technicien.html' : 'register.html';
            return new Response(file_get_contents(__DIR__ . '/../views/' . $view), 200, ['Content-Type' => 'text/html']);
        }

        try {
            $databaseConnexion = new DatabaseConnexion();
            $databaseConnexion->setConnexion();
            $pdo = $databaseConnexion->getConnexion();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $user = new User();

        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $email = $request->get('email');
        $telephone = $request->get('telephone');
        $motDePasse = $request->get('motDePasse');
        $role = $request->getPath() === '/register-technicien' ? 'technicien' : 'utilisateur';

        if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($motDePasse) || empty($role)) {
            error_log('Validation failed:');
            error_log('Nom: ' . ($nom ? $nom : 'empty'));
            error_log('Prenom: ' . ($prenom ? $prenom : 'empty'));
            error_log('Email: ' . ($email ? $email : 'empty'));
            error_log('Telephone: ' . ($telephone ? $telephone : 'empty'));
            error_log('MotDePasse: ' . ($motDePasse ? $motDePasse : 'empty'));
            error_log('Role: ' . ($role ? $role : 'empty'));
            return new Response(json_encode(['message' => 'Tous les champs sont obligatoires.']), 400, ['Content-Type' => 'application/json']);
        }

        $existingUser = $userManager->findByEmail($email);
        if ($existingUser) {
            return new Response(json_encode(['message' => 'Cette adresse e-mail est déjà utilisée.']), 400, ['Content-Type' => 'application/json']);
        }

        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setTelephone($telephone);
        $user->setMotDePasse(password_hash($motDePasse, PASSWORD_BCRYPT));
        $user->setValidationMail(false);
        $user->setRole($role);

        $userManager->save($user);

        $config = json_decode(file_get_contents(__DIR__ . '/../../config/database.json'), true);
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp']['username'];
            $mail->Password = $config['smtp']['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp']['port'];

            $mail->setFrom('no-reply@yourdomain.com', 'Your App Name');
            $mail->addAddress($user->getEmail());

            $mail->isHTML(true);
            $mail->Subject = 'Validation de votre compte';
            $validationLink = "http://localhost:8080/validate.php?email=" . $user->getEmail();
            $mail->Body = "Cliquez sur ce lien pour valider votre compte : <a href='$validationLink'>$validationLink</a>";

            $mail->send();
        } catch (Exception $e) {
            return new Response(json_encode(['message' => 'Erreur lors de l\'envoi de l\'e-mail de validation : ' . $mail->ErrorInfo]), 500, ['Content-Type' => 'application/json']);
        }

        return new Response('', 302, ['Location' => '/login']);
    }
}