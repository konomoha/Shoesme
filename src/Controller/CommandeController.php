<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Repository\CommandeRepository;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        if(!empty($panier))
        {
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
           
        }
         
        else
        {
            return $this->redirectToRoute('home');
        }
        
            return $this->render('commande/commande.html.twig', [
                "dataCommande"=>$dataCommande,
                "total"=>$total, 
            ]
        );
    }

    #[Route('/commande/paiement', name: 'commande_paiement')]
    public function commandePaiement(SessionInterface $session, ChaussureRepository $chaussureRepo, Commande $commande=null, DetailsCommande $detailCommande=null, EntityManagerInterface $manager, User $user=null): Response
    {
        if($this->getUser())
        {
            $commande = new Commande;
            
            $panier = $session->get("panier", []);
            $total=0;
            
            $user = $this->getUser();
            $prenom = $this->getUser()->getPrenom(); 
            
            // la méthode getPrenom est considérée comme inconnue, mais elle existe bel et bien.
            // dd($prenom);

            $dataCommande = [];
            $total = 0;
            $chaussure="";

            if(!empty($panier))
            {

                foreach($panier as $id=>$quantite)
                {
                    $chaussure= $chaussureRepo->find($id);
                    $dataCommande[]= [
                        "Chaussure"=> $chaussure,
                        "Quantite"=>$quantite
                    ]; 
                    $total += $chaussure->getPrix() * $quantite; 
                    // dd($dataCommande);
                }
                
                $commande->setMontant($total)
                            ->setEtat('envoyé')
                            ->setUser($user)
                            ->setDate(new \DateTime());
                $manager->persist($commande);
                $manager->flush();

                foreach($dataCommande as $data)
                {
                    // dump($data["Chaussure"]->getPrix());
                    $detailCommande = new DetailsCommande;//une nouvelle ligne detailscommande par référence
                    $detailCommande->setQuantite($data["Quantite"])
                                ->setPrix($data["Chaussure"]->getPrix())
                                ->setCommande($commande)
                                ->setChaussure($data["Chaussure"]);
                    $manager->persist($detailCommande);
                    $manager->flush();

                    $nouveaustock = $chaussure->getStock() - $data["Quantite"];
                    // dd($nouveaustock);
                    $chaussure->setStock($nouveaustock);
                    // dd($chaussure);
                    $manager->persist($chaussure);
                    $manager->flush();
                    //    dump($detailCommande);
                }

                $this->addFlash('success_payment', "Félicitations $prenom! Votre paiement a bien été effectué!");

                $session->remove("panier"); //On vide le panier une fois le paiement effectué et la commande enregistré en bdd
            }
            
            //Si le panier est vide, on redirige vers l'accueil
            else
            {
                return $this->redirectToRoute('home');
            }
        }

        else
        {
            return $this->redirectToRoute('home');
        }

        return $this->render('commande/paiement_success.html.twig',[
            "panier"=>$panier
        ]
    );
    }

    #[Route('/commande/{id}', name: 'commande_historique')]
    public function commandeHistory(User $user=null, CommandeRepository $commandeRepo):Response
    {

        $dataCommande="";

        if($this->getUser())
        {
            $id = $user->getId();
            $dataCommande = $commandeRepo->findAll($id);
            // dd($dataCommande);
        }
        else{
            return $this->redirectToRoute('home');
        }

        return $this->render('commande/commande_historique.html.twig', [
            "dataCommande"=>$dataCommande
        ]
    );
    }
        
}