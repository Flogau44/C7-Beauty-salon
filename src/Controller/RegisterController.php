<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegisterController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $firstname = $data['firstname'] ?? '';
        $name = $data['name'] ?? '';

        // Vérification des champs obligatoires
        if (empty($firstname)) {
            return $this->json(['error' => 'Le champ "firstname" est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }

        if (empty($name)) {
            return $this->json(['error' => 'Le champ "name" est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Adresse e-mail invalide.'], Response::HTTP_BAD_REQUEST);
        }

        // Vérification avancée du mot de passe
        $passwordErrors = $this->validatePassword($password);

        if (!empty($passwordErrors)) {
            return $this->json(['error' => $passwordErrors], Response::HTTP_BAD_REQUEST);
        }

        // Création de l'utilisateur
        $user = new User();
        $user->setFirstname($firstname);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        // Ajouter le rôle par défaut
        $user->setRoles(['ROLE_USER']);

        // Sauvegarde de l'utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Envoi de l'email de confirmation
        $this->sendConfirmationEmail($user);

        return $this->json(['message' => 'Inscription réussie. Email de confirmation envoyé.'], Response::HTTP_CREATED);
    }

    private function validatePassword(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
        }
        if (!preg_match('/\d/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
        }
        if (!preg_match('/[\W]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }

        return $errors;
    }

    private function sendConfirmationEmail(User $user)
    {
        $email = (new Email())
            ->from('no-reply@beauty-salon.com')
            ->to($user->getEmail())
            ->subject('Confirmation d\'inscription')
            ->html("<p>Bonjour {$user->getFirstname()},</p><p>Merci pour votre inscription !</p><p>Votre compte est maintenant actif.</p>");

        $this->mailer->send($email);
    }
}