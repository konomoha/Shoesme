<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetailsArticlesController extends AbstractController
{
    #[Route('/details_articles/{id}', name: 'details_articles')]
    public function index(ChaussureRepository $repoChaussure): Response
    {
        $chaussure = $repoChaussure->findAll(); // offset

        return $this->render('details_articles/details_articles.html.twig', [
            'chaussure'=> $chaussure
        ]);
    }
}
