<?php

namespace App\Controller;

use PDOException;
use App\Entity\User;
use App\Entity\Chaussure;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    //Le principe de cette méthode panier est de se servir d'un cookie de la session php, une superglobal pour stocker directement les données des produits ajoutés au panier d'une page à l'autre. 
    #[Route('/panier', name: 'panier')]
    public function index(Request $request, SessionInterface $session, ChaussureRepository $chaussureRepo): Response
    {
        //Récupération du panier via la méthode get qui peut contenir plusieurs paramètres.Soit $panier vaudra le contenu de la session ("panier"), soit il contiendra un array vide.
        $panier = $session->get("panier", []);
        //on créée une variable qui va contenir chaque produit du panier.On transmettra ensuite ce tableau sur le template.
        $dataPanier = [];

        $total = 0;
        
        //On crée une boucle sur $panier afin d'en récupérer les $id et les $quantités associées.
        foreach($panier as $id=>$quantite)
        {
            //On utilise ici la méthode find() du repo de l'entité Chaussure et on lui passe en paramètre $id défini via la boucle foreach. Le résultat est ensuite stocké dans la variable $chaussure, il correspond à toutes les données de la chaussure portant cet id.
            $chaussure= $chaussureRepo->find($id);

            //On stock ensuie dans le tableau dataPanier des indices auxquels on attribue les variables $chaussures et $quantite (variable établie via la boucle foreach).
            $dataPanier[]= [
                "Chaussure"=> $chaussure,
                "Quantite"=>$quantite
            ]; 

            //On ajoute dans la variable $total déclarée plus haut le prix de la chaussure multiplié par la quantité.
            $total += $chaussure->getPrix() * $quantite;
        }
        
        return $this->render('panier/panier.html.twig', [
            "dataPanier"=>$dataPanier,
            "total"=>$total
        ]
        );
    }

    //On définit ici une route pour l'ajout de chaussures au panier.On ajoute en paramètre un id qui réceptionnera l'id de la Chaussure sélectionnée en boutique. On utilise également la session php  pour stocker et véhiculer les informations du produit sélectionné d'une page à l'autre.

    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function addPanier(Chaussure $chaussure, ChaussureRepository $chaussureRepo, SessionInterface $session, EntityManagerInterface $manager): Response
    {
        //On vérifie d'abord que l'utilisateur est bien connecté via la méthode getUser()
        if($this->getUser())
        {
            //On vérifie d'abord que le stock de chaussure n'est pas nul.
            if($chaussure->getStock() > 0)
            {
                $id = $chaussure->getId();
                // dd($id);

                $model = $chaussure->getModel();

                //Récupération du panier via la méthode get qui peut contenir plusieurs paramètres.Soit $panier vaudra le contenu de la session ("panier"), soit il contiendra un array vide.
                $panier = $session->get("panier", []);
                
                //On met en place ici une condition pour vérifier si l'id passé dans l'url existe déjà. si c'est le cas, on incrémente.
                if(!empty($panier[$id]))
                {
                    $panier[$id]++;

                }

                //Sinon, l'id n'existe pas dans le panier. Dans ce cas-là, on le crée.
                else
                {
                    $panier[$id] = 1;
                
                }
                
                //La quantité est ensuite sauvegardée dans la session.
                $session->set("panier", $panier);
                
                $this->addFlash('success', "$model a bien été ajouté au panier!");

                //La méthode nous renvoie directement sur la page panier
                return $this->redirectToRoute('panier');
            }

            else //si un utilisateur tente d'ajouter manuellement des produit sur une référence au stock nul via l'URL, on le redirige vers la page panier.
            {
                return $this->redirectToRoute('panier');
            }
        }

        //Si un utilisateur tente d'ajouter des produits au panier mais qu'il n'est pas connecté, on le redirige vers la page de connexion.
        else
        {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }


    #[Route('/panier/remove/{id}', name:'panier_remove')]
    public function removePanier(Chaussure $chaussure, SessionInterface $session): Response
    {
        //Méthode de suppression de produit dans le panier. Cette méthode est similaire à l'ajout.
        $id = $chaussure->getId();
      
        $panier = $session->get("panier", []);
        
        //Mise en place d'une première condition afin de vérifier que le panier contient bien des produits.
        if(!empty($panier[$id]))
        {
            //Si la quantité de produits présent dans le panier est supérieure à un, on réduit la quantité de une unité.
            if($panier[$id] > 1)
            {
                $panier[$id]--;

            }
            //Sinon, on supprime le panier
            else
            {
                unset($panier[$id]);
               
            }
            
        }
        else
        {
            return $this->redirectToRoute('home');
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
        //IL s'agit ici d'une suppression totale d'une référence dans le panier.
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
