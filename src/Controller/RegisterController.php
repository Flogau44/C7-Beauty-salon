<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): Response
{
    $data = json_decode($request->getContent(), true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $firstname = $data['firstname'] ?? '';
    $name = $data['name'] ?? '';

    // Vérification si le champ name est vide
    if (empty($firstname)) {
        return $this->json(['error' => 'Le champ "firstname" est obligatoire.'], Response::HTTP_BAD_REQUEST);
    }

    // Vérification si le champ name est vide
    if (empty($name)) {
        return $this->json(['error' => 'Le champ "name" est obligatoire.'], Response::HTTP_BAD_REQUEST);
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $this->json(['error' => 'Adresse e-mail invalide.'], Response::HTTP_BAD_REQUEST);
    }

    // Validation du mot de passe
    if (strlen($password) < 8) {
        return $this->json(['error' => 'Le mot de passe doit contenir au moins 8 caractères.'], Response::HTTP_BAD_REQUEST);
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

    return $this->json(['message' => 'Inscription réussie.'], Response::HTTP_CREATED);
}
}
