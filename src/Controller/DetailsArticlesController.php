<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsArticlesController extends AbstractController
{
    #[Route('/details_articles', name: 'details_articles')]
    public function index(): Response
    {
        return $this->render('details_articles/details_articles.html.twig', [
            // 'controller_name' => 'DetailsArticlesController',
        ]);
    }
}
