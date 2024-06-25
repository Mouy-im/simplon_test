<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlagueController extends AbstractController
{
    #[Route('/blague', name: 'app_blague')]
    public function index(): Response
    {
        return $this->render('blague/index.html.twig', [
            'controller_name' => 'BlagueController',
        ]);
    }
}
