<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FemmeController extends AbstractController
{
    #[Route('/femme', name: 'femme')]
    public function index(ChaussureRepository $repoChaussure): Response
    {
        // ////////////////////////////////////////////////METHODE AFFICHAGE LIMIT /////////////////////////////////////

        $chaussure = $repoChaussure->findBy(
            array(), // condition where
            array (), //order by
            1000, // la limite de chaussures à afficher
            0); // offset

        return $this->render('femme/femme.html.twig', [
            'chaussure'=>$chaussure
        ]);
    }
}

// class FemmeController extends AbstractController
// {
//     #[Route('/femme', name: 'femme')]
//     public function index(): Response
//     {
//         return $this->render('femme/femme.html.twig', [
//             'controller_name' => 'FemmeController',
//         ]);
//     }
// }
