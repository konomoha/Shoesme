<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoesMeController extends AbstractController
{
    #[Route('/shoes/me', name: 'shoes_me')]
    public function index(): Response
    {


        return $this->render('shoes_me/home.html.twig');

    }
}

