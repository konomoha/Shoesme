<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HommeController extends AbstractController
{
    #[Route('/homme', name: 'homme')]
    public function index(ChaussureRepository $repoChaussure): Response
    {
        ////////////////////////////////////////////////METHODE AFFICHAGE LIMIT /////////////////////////////////////

        $chaussure = $repoChaussure->findBy(
            array(), // condition where
            array (), //order by
            6, // la limite de chaussures à afficher
            0); // offset
         
        return $this->render('homme/homme.html.twig', [
            'chaussure'=>$chaussure
        ]);
    }
}
