<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GarconController extends AbstractController
{
    #[Route('/garcon', name: 'garcon')]
    public function index(): Response
    {
        return $this->render('garcon/garcon.html.twig', [
            'controller_name' => 'GarconController',
        ]);
    }
}
