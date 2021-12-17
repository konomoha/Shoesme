<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;
use Symfony\Component\Routing\Annotation\Route;

class ShoesMeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {


        return $this->render('shoes_me/home.html.twig');

    }

    #[Route ('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('shoes_me/contact.html.twig');
    }
}

