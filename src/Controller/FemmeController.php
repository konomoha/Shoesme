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

        $chaussure = $repoChaussure->findAll();
         
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
