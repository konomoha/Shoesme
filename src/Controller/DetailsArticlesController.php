<?php

namespace App\Controller;

use App\Entity\Chaussure;
use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetailsArticlesController extends AbstractController
{
    // #[Route('/details_articles/{id}', name: 'details_articles')]
    // public function index(ChaussureRepository $repoChaussure, Chaussure $chaussure): Response
    // {
        

    //     $chaussure1 = $repoChaussure->findAll(); // offset

    //     $shoes = $chaussure->getId();
        
        
    //     return $this->render('details_articles/details_articles.html.twig', [
    //         'chaussure'=> $chaussure1,
    //         'id' => $shoes
    //     ]);
    // }
}
