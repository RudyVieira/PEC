<?php

namespace App\Controllers;

use App\Entities\User;
use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\UserManager;
use App\Lib\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PDOException;

class RegisterController extends AbstractController {

    public function process(Request $request): Response {
        if ($request->getMethod() === 'GET') {
            $view = $request->getPath() === '/register-technicien' ? 'register-technicien.html' : 'register.html';
            return new Response(file_get_contents(__DIR__ . '/../Views/' . $view), 200, ['Content-Type' => 'text/html']);
        }

        try {
            $pdo = Database::getConnection();
        } catch (PDOException $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }

        $userManager = new UserManager($pdo);
        $user = new User();
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setEmail($request->get('email'));
        $user->setMotDePasse(password_hash($request->get('motDePasse'), PASSWORD_BCRYPT));
        $user->setValidationMail(false);
        $user->setRole($request->get('role'));

        $userManager->save($user);

        // Envoyer l'e-mail de validation
        $config = json_decode(file_get_contents(__DIR__ . '/../../config/database.json'), true);
        try {
            // Tentative de création d’une nouvelle instance de la classe PHPMailer, avec les exceptions activées
            $mail = new PHPMailer (true);
        // (…)
        } catch (Exception $e) {
                echo "Mailer Error: ".$e->getMessage();
        }

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = $config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp']['username'];
            $mail->Password = $config['smtp']['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp']['port'];

            // Destinataires
            $mail->setFrom('no-reply@yourdomain.com', 'Your App Name');
            $mail->addAddress($user->getEmail());

            // Contenu de l'e-mail
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