<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HommeController extends AbstractController
{
    #[Route('/homme', name: 'homme')]
    public function index(): Response
    {
        return $this->render('homme/homme.html.twig', [
            'controller_name' => 'HommeController',
        ]);
    }
}
