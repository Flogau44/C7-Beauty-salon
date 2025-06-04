<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SalesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

final class HistoryController extends AbstractController
{
    /**
     * Récupère l'historique des ventes de tous les salons (réservé aux administrateurs)
     */
    #[Route('/api/history/all', name: 'history', methods: ['GET'])]
    public function getHistory(SalesRepository $salesRepository, SerializerInterface $serializer): JsonResponse
    {
        $salesHistory = $salesRepository->findAll();
        if (!$salesHistory) {
            return new JsonResponse(['message' => 'Aucun chiffre d\'affaires disponible'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($salesHistory, Response::HTTP_OK, [], ['groups' => 'getHistory']);
    }

    /**
     * Récupère l'historique des ventes du salon de l'utilisateur connecté
     */
    #[Route('/api/history', name: 'currentUserHistory', methods: ['GET'])]
    public function getHistoryByUser(#[CurrentUser] ?User $user, SerializerInterface $serializer, SalesRepository $salesRepository): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupération des ventes du salon associé à l'utilisateur
        $userSales = $salesRepository->findBy(['user' => $user]);
        if (!$userSales) {
            return new JsonResponse(['message' => 'Aucune vente enregistrée pour cet utilisateur'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($userSales, Response::HTTP_OK, [], ['groups' => 'getHistory']);
    }
}