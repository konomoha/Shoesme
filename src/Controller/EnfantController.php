<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EnfantController extends AbstractController
{
    #[Route('/enfant', name: 'enfant')]
    public function index(ChaussureRepository $chaussureRepo): Response
    {
         
        $shoes_fille = $chaussureRepo->findBy(
            array('sexe' => 'fille'), // condition where
            array (), //order by
            5, // la limite de chaussures à afficher
            0); // offset
        
        
        $shoes_garcon = $chaussureRepo->findBy(
            array('sexe' => 'g'), // condition where
            array (), //order by
            5, // la limite de chaussures à afficher
            0); // offset
         
        return $this->render('shoes_me/enfant.html.twig', [
            'chaussure_fille'=> $shoes_fille,
            'chaussure_garcon'=> $shoes_garcon,
        ]);
    }
}
