<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FemmeController extends AbstractController
{
    #[Route('/femme', name: 'femme')]
    public function index(): Response
    {
        return $this->render('femme/femme.html.twig', [
            'controller_name' => 'FemmeController',
        ]);
    }
}
