<?php

namespace App\Controller;

use App\Entity\Blague;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/blague')]
class BlagueController extends AbstractController
{
    #[Route('/', name: 'blague.list')]
    public function index(): Response
    {
        return $this->render('blague/index.html.twig', [
            'controller_name' => 'BlagueController',
        ]);
    }

    #[Route('/add', name: 'blague.add')]
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


}
