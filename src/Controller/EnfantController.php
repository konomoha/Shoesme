<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EnfantController extends AbstractController
{
    #[Route('/enfant', name: 'enfant')]
    public function index(ChaussureRepository $repoChaussure): Response
    {
        ////////////////////////////////////////////////METHODE AFFICHAGE LIMIT /////////////////////////////////////

        $chaussure = $repoChaussure->findBy(
            array(), // condition where
            array (), //order by
            57, // la limite de chaussures à afficher
            0); // offset
         
        return $this->render('enfant/enfant.html.twig', [
            'chaussure'=> $chaussure
        ]);
    }
}
