<?php

namespace App\Controller;

use App\Entity\Chaussure;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BackofficeController extends AbstractController
{
/* ################## Route Ecran Accueil Back-office ################## */
    #[Route('/backoffice', name: 'backoffice')]
    public function index(): Response
    {
        return $this->render('backoffice/home.html.twig', [
            'title' => 'BackofficeAcces',
        ]);
    }

/* ################## ROUTE AFFICHAGE ET SUPPRESSION CHAUSSURE ################## */    
    #[Route('/backoffice/produit', name: 'backoffice_produit_affichage')]
    #[Route('/backoffice/produit/suppression/{id}', name: 'backoffice_produit_suppression')]
    public function backOfficeProduit(EntityManagerInterface $manager, Chaussure $shoesRemove=null, ChaussureRepository $chaussureRepo)
    {
        //Affichage chaussures
        $titreColonne=$manager->getClassMetadata(Article::class)->getFieldNames();
        $shoes = $chaussureRepo->findAll();

        //Suppression chaussures
        if($shoesRemove)
        {
            $id=$shoesRemove->getId();
            $manager->remove($shoesRemove);
            $manager->flush();
            $this->addFlash('success', "La chaussure n° $id a été supprimée");

            return $this->redirectToRoute('backoffice_produit_affichage');
        }
        //Fin suppression chaussure

        return $this->render('backoffice/chaussure.html.twig', [
            'colonne'=>$titreColonne,
            'chaussure'=>$shoes
        ]);
    }
/* ################## FIN ROUTE AFFICHAGE ET SUPPRESSION CHAUSSURE ################## */

/* ################## ROUTE AJOUT/MODIFICATION CHAUSSURE ################## */
    #[Route('/backoffice/produit/ajout', name: 'backoffice_produit_ajout')]
    #[Route('/backoffice/produit/modification/{id}', name: 'backoffice_produit_modification')]
    public function backOfficeProduitForm(Chaussure $shoes=null, Request $request,EntityManagerInterface $manager)
    {
        if($shoes)
        {
            $photoEnregistree = $shoes->getPhoto();
        }
        if(!$shoes)
        {
            $shoes = new Chaussure;
        }

        $formAdminShoes = $this->createForm(ChaussureType::class, $shoes);
        $formAdminShoes->handleRequest($request);

        if($formAdminShoes->isSubmitted() && $formAdminShoes->isValid())
        {
            $shoes=$this->getUser();
            if($shoes->getId() )
                $txt = 'modifiée';
            else 
                $txt = 'enregistrée';

            //***Traitement Photo ***/ 
            $photo = $formAdminShoes->get('photo')->getData();
            if($photo)
            {
                $nomOriginePhoto = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNomFichier = $nomOriginePhoto . '-' . uniqid() . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('photo_directory'), $nouveauNomFichier);
                $shoes->setPhoto($nouveauNomFichier);
            }
            else 
            { 
                if(isset($photoEnregistree))
                    $shoes->setPhoto($photoEnregistree);
                else  
                    $shoes->setPhoto(null);
            }
            //***FIN Traitement Photo ***/ 

            $manager->persist($shoes);
            $manager->flush();

            $this->addFlash('success', "La chaussure a été $txt avec succès.");

            return $this->redirectToRoute('backoffice_produit_affichage');
        }

        return $this->render('backoffice/admin_produit_form.html.twig', [
            'formAdminShoes' => $formAdminShoes->createView(),
            'photoEnregistree' => $shoes->getPhoto(), 
            'Modification' => $shoes->getId()
        ]);
    }
/* ################## FIN ROUTE AJOUT/MODIFICATION CHAUSSURE ################## */
}
