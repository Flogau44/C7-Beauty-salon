<?php

namespace App\Controller;

use App\Entity\Sales;
use App\Entity\User;
use App\Repository\SalonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class SalesController extends AbstractController
{
    #[Route('/api/new_entry', name: 'newEntry', methods: ['POST'])]
    public function createNewEntry(
        #[CurrentUser] ?User $user,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        SalonRepository $salonRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        // Vérification des données reçues
        $content = json_decode($request->getContent(), true);
        if (!$content || !isset($content['amount'], $content['entryDate'], $content['salon'])) {
            return new JsonResponse(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        // Vérification que le montant est positif
        if ($content['amount'] <= 0) {
            return new JsonResponse(['error' => 'Le montant doit être supérieur à zéro'], Response::HTTP_BAD_REQUEST);
        }

        // Vérification que la date correspond au mois précédent
        $entryDate = \DateTimeImmutable::createFromFormat('m-Y', $content['entryDate']);
        $lastMonth = new \DateTimeImmutable('first day of last month');
        if (!$entryDate || $entryDate->format('m-Y') !== $lastMonth->format('m-Y')) {
            return new JsonResponse(['error' => 'La date doit correspondre au mois précédent'], Response::HTTP_BAD_REQUEST);
        }

        // Vérification du salon et de son propriétaire
        $salon = $salonRepository->find($content['salon']);
        if (!$salon || $salon->getUser() !== $user) {
            return new JsonResponse(['error' => 'Salon invalide ou non autorisé'], Response::HTTP_FORBIDDEN);
        }

        // Création de la vente
        $sale = new Sales();
        $sale->setAmount($content['amount']);
        $sale->setSalonId($salon);
        $sale->setCreatedAt($entryDate);

        // Validation des données
        $errors = $validator->validate($sale);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        // Persistance en base
        $em->persist($sale);
        $em->flush();

        return new JsonResponse(['message' => 'Chiffre d\'affaires enregistré'], Response::HTTP_CREATED);
    }

    /**
     * Supprime une entrée de chiffre d'affaires (réservé aux admins)
     */
    #[Route('/api/effacer-saisie/{id}', name: 'deleteEntry', methods: ['DELETE'])]
    public function deleteEntry(Sales $sales, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($sales);
        $em->flush();

        return new JsonResponse(['message' => 'Entrée supprimée'], Response::HTTP_NO_CONTENT);
    }
}