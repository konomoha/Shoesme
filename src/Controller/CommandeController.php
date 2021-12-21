<?php

namespace App\Controller;

use App\Repository\ChaussureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande')]
    public function commande(SessionInterface $session, ChaussureRepository $chaussureRepo): Response
    {
        $panier = $session->get("panier", []);

        $dataCommande = [];
        $total = 0;
        foreach($panier as $id=>$quantite)
        {
            $chaussure= $chaussureRepo->find($id);
            $dataCommande[]= [
                "Chaussure"=> $chaussure,
                "Quantite"=>$quantite
            ]; 
            $total += $chaussure->getPrix() * $quantite; //le prix de l'article multiplié par la quantité
        }
            
           
    
            return $this->render('commande/commande.html.twig', [
                "dataCommande"=>$dataCommande,
                "total"=>$total
            ]
        );
    }
        
}
