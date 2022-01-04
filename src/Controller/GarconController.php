<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GarconController extends AbstractController
{
    #[Route('/garcon', name: 'garcon')]
    public function index(ChaussureRepository $repoChaussure): Response
    {
        $chaussure = $repoChaussure->findAll();

        return $this->render('garcon/garcon.html.twig', [
            'chaussure'=> $chaussure,
        ]);
    }
}
