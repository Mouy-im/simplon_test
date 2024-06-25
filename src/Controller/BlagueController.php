<?php

namespace App\Controller;

use App\Entity\Blague;
use App\Repository\BlagueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/blagues')]
class BlagueController extends AbstractController
{
    #[Route('/', name: 'blagues.list')]
    public function index(
        BlagueRepository $blagueRepository,
        SerializerInterface $serializer

    ): Response
    {
        $blagues = $blagueRepository->findAll();
       
        // Conversion des objets en JSON
        $jsonContent = $serializer->serialize($blagues, 'json', ['groups' => 'blague:read']);
        
        return new JsonResponse($jsonContent, 200, [], true);

    }

    #[Route('/add', name: 'blagues.add')]
    public function addBlague(
        Request $request,
        ManagerRegistry $doctrine
    ): JsonResponse
    {
        $blague = new Blague();
        $blague->setQuestion($request->query->get('question'))
               ->setResponse($request->query->get('response'))
               ->setCreatedAt(new \DateTimeImmutable());

        $entityManager = $doctrine->getManager();
            
        $entityManager->persist($blague);

        $entityManager->flush();

         return new JsonResponse([
            'success' => true,
            'message' => 'Blague ajoutée avec succès',
            'blague' => [
                'id' => $blague->getId(),
                'question' => $blague->getQuestion(),
                'response' => $blague->getResponse(),
                'createdAt' => $blague->getCreatedAt()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    #[Route('/{id<\d+>}', name: 'blagues.view')]
    public function viewBlagueById(
        Blague $blague = null,
        SerializerInterface $serializer
    ): JsonResponse
    {
        // Conversion des objets en JSON
        $jsonContent = $serializer->serialize($blague, 'json', ['groups' => 'blague:read']);
        
        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/random', name: 'blagues.random')]
    public function viewRandomBlague(
        BlagueRepository $blagueRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        
        $blague = $blagueRepository->findRandomBlague();
        // Conversion des objets en JSON
        $jsonContent = $serializer->serialize($blague, 'json', ['groups' => 'blague:read']);
        
        return new JsonResponse($jsonContent, 200, [], true);
    }


}
