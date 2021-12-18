<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;

use App\Entity\Chaussure;

use App\Form\ChaussureType;

use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BackofficeController extends AbstractController
{
/* ##################------------ Route Ecran Accueil Back-office ------------################## */
    #[Route('/backoffice', name: 'backoffice')]
    public function index(): Response
    {
        return $this->render('backoffice/admin_home.html.twig', [
            'title' => 'BackofficeAcces',
        ]);
    }

/* ##################------------ CRUD - CHAUSSURE ------------################## */   

/* ################## ROUTE AFFICHAGE ET SUPPRESSION ################## */
    #[Route('/backoffice/produit', name: 'backoffice_produit')]
    #[Route('/backoffice/produit/suppression/{id}', name: 'backoffice_produit_suppression')]
    public function backOfficeProduit(EntityManagerInterface $manager, Chaussure $shoesRemove=null, ChaussureRepository $chaussureRepo)
    {
        //Affichage chaussures
        $titreColonne=$manager->getClassMetadata(Chaussure::class)->getFieldNames();
        $shoes = $chaussureRepo->findAll();

        //Suppression chaussures
        if($shoesRemove)
        {
            $id=$shoesRemove->getId(). ' - ' . $shoesRemove->getMarque(). ' - ' . $shoesRemove->getModel();
            $manager->remove($shoesRemove);
            $manager->flush();
            $this->addFlash('suppression', "La chaussure n° $id a été supprimée");

            return $this->redirectToRoute('backoffice_produit');
        }
        //Fin suppression chaussure

        return $this->render('backoffice/chaussure.html.twig', [
            'colonne'=>$titreColonne,
            'chaussure'=>$shoes
        ]);
    }


/* ################## ROUTE AJOUT ET MODIFICATION ################## */
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

            return $this->redirectToRoute('backoffice_produit');
        }

        return $this->render('backoffice/chaussureAjout.html.twig', [
            'formAdminShoes' => $formAdminShoes->createView(),
            'photoEnregistree' => $shoes->getPhoto(), 
            'Modification' => $shoes->getId()
        ]);
    }

/* ##################------------ FIN - CRUD - CHAUSSURE ------------################## */  


/* affichage des message de contact */

#[Route('/backoffice/message', name: 'app_message')]

    public function messageView(EntityManagerInterface $manager, ContactRepository $repoContact)
    {

        $colonnes = $manager->getclassMetadata(Contact::class)->getFieldNames();

        $cellules = $repoContact->findAll();


        return $this->render('backoffice/admin_message.html.twig', [
            'colonnes' => $colonnes,
            'cellules' => $cellules
        ]);

      
    }

    #[Route('/backoffice/message/{id}/delete', name: 'app_delete_message')]
    public function deleteMessage(EntityManagerInterface $manager, Contact $contactRemove)
    {
        if($contactRemove)
        
            $id = $contactRemove->getId();

            $manager->remove($contactRemove);
            $manager->flush();

            $this->addFlash('success', "Le commentaire $id a bien été supprimer avec succès");

            return $this->redirectToRoute('app_message');
       
    }

    #[Route('backoffice/user', name: 'app_admin_user')]

    public function userView(EntityManagerInterface $manager, UserRepository $repoUser)
    {

        $colonnes = $manager->getclassMetadata(User::class)->getFieldNames();

        $cellules = $repoUser->findAll();


        return $this->render('backoffice/admin_user.html.twig', [
            'colonnes' => $colonnes,
            'cellules' => $cellules
        ]);

      
    }

}
