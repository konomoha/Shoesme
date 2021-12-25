<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commande;
use App\Entity\DetailsCommande;
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
            $detailCommande = new DetailsCommande;
            $panier = $session->get("panier", []);
            $total=0;
            
            $user = $this->getUser();
            $prenom = $this->getUser()->getPrenom(); 
            
            // la méthode getPrenom est considérée comme inconnue, mais elle existe bel et bien.
            // dd($prenom);

            $dataCommande = [];
            $total = 0;
            $qte = 0;
            $prix ="";
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
                    $prix = $chaussure->getPrix();
                    $total += $chaussure->getPrix() * $quantite; 
                    // dd($dataCommande);
                }
                
                $qte=0;

                foreach($dataCommande as $key=>$value)
                {
                    // dd($value);
                    foreach($value as $key=>$data)
                    {
                        if($key == 'Quantite')
                        {
                            // dd($data);
                            $qte= $data;
                            
                        }
                    }
                }
                
                $commande->setMontant($total)
                            ->setEtat('envoyé')
                            ->setUser($user);
                $manager->persist($commande);
                $manager->flush();
            
                $detailCommande->setQuantite($qte)
                                ->setPrix($prix)
                                ->setCommande($commande);
                $manager->persist($detailCommande);
                $manager->flush();

                $nouveaustock = $chaussure->getStock() - $qte;
                // dd($nouveaustock);
                $chaussure->setStock($nouveaustock);
                // dd($chaussure);
                $manager->persist($chaussure);
                $manager->flush();
                //    dd($detailCommande); 

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

        
}
