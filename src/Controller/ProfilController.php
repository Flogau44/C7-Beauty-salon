<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ProfilController extends AbstractController
{
    /**
     * Récupère les données du profil de l'utilisateur connecté
     */
    #[Route('/api/profil', name: 'currentProfil', methods: ['GET'])]
    public function getCurrentProfil(#[CurrentUser] ?User $user, SerializerInterface $serializer): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $jsonProfil = $serializer->serialize($user, 'json', ['groups' => 'getProfil']);
        return new JsonResponse($jsonProfil, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * Met à jour les informations du profil de l'utilisateur connecté
     */
    #[Route('/api/profil', name: 'updateProfil', methods: ['PUT'])]
    public function updateProfil(
        Request $request,
        #[CurrentUser] ?User $user,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'Profil mis à jour'], Response::HTTP_NO_CONTENT);
    }
}