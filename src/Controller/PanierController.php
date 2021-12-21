<?php

namespace App\Controller;

use App\Entity\Chaussure;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(SessionInterface $session, ChaussureRepository $chaussureRepo): Response
    {
        $panier = $session->get("panier", []);

        $dataPanier = [];
        $total = 0;
        foreach($panier as $id=>$quantite)
        {
            $chaussure= $chaussureRepo->find($id);
            $dataPanier[]= [
                "Chaussure"=> $chaussure,
                "Quantite"=>$quantite
            ]; 
            $total += $chaussure->getPrix() * $quantite; //le prix de l'article multiplié par la quantité
        }

        return $this->render('panier/panier.html.twig', [
            "dataPanier"=>$dataPanier,
            "total"=>$total
        ]
    );
    }

    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function addPanier(Chaussure $chaussure, SessionInterface $session, EntityManagerInterface $manager): Response
    {
        $id = $chaussure->getId();
        $model = $chaussure->getModel();
        $panier = $session->get("panier", []);
        
        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }

        else
        {
            $panier[$id] = 1;

        }
      
      //sauvegarde du panier
      $session->set("panier", $panier);
      $session->set("commande", $panier);
 
      $this->addFlash('success', "$model a bien été ajouté au panier!");

      return $this->redirectToRoute('panier');

        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/panier/remove/{id}', name:'panier_remove')]
    public function removePanier(Chaussure $chaussure, SessionInterface $session): Response
    {
        $id = $chaussure->getId();
      
      $panier = $session->get("panier", []);
        
        if(!empty($panier[$id]))
        {
            if($panier[$id] > 1)
            {
                $panier[$id]--;
            }
            else
            {
                 unset($panier[$id]);
            }
            
        }

      //sauvegarde du panier
      $session->set("panier", $panier);

      return $this->redirectToRoute('panier');

        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/panier/delete/{id}', name:'panier_delete')]
    public function deletePanier(Chaussure $chaussure, SessionInterface $session): Response
    {
        $id = $chaussure->getId();
      
      $panier = $session->get("panier", []);
        
        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }

      
      $session->set("panier", $panier);

      return $this->redirectToRoute('panier');

        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

}
