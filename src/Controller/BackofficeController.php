<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Chaussure;
use App\Entity\Commentaire;

use App\Form\ChaussureType;
use App\Form\CommentFormType;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        $titreColonneChaussure=$manager->getClassMetadata(Chaussure::class)->getFieldNames();
        $shoes = $chaussureRepo->findAll();

        //Suppression chaussures
        if($shoesRemove)
        {
            $id=$shoesRemove->getId()//. ' - ' . $shoesRemove->getMarque(). ' - ' . $shoesRemove->getModel()
            ;
            $manager->remove($shoesRemove);
            $manager->flush();
            $this->addFlash('suppression', "La chaussure n° $id a été supprimée");

            return $this->redirectToRoute('backoffice_produit');
        }
        //Fin suppression chaussure

        return $this->render('backoffice/admin_article.html.twig', [
            'colonneChaussure'=>$titreColonneChaussure,
            'chaussure'=>$shoes
        ]);
    }


/* ################## ROUTE AJOUT ET MODIFICATION ################## */
    #[Route('/backoffice/produit/ajout', name: 'backoffice_produit_ajout')]
    #[Route('/backoffice/produit/modification/{id}', name: 'backoffice_produit_modification')]
    public function backOfficeProduitForm(Chaussure $shoes=null, Request $request,EntityManagerInterface $manager, SluggerInterface $slugger)
    {
        if($shoes)
        {
            $photoEnregistree = $shoes->getPhoto();
            $photoEnregistree2= $shoes->getPhoto2();
            $photoEnregistree3= $shoes->getPhoto3();
            $photoEnregistree4= $shoes->getPhoto4(); 
        }

        if(!$shoes)
        {
            $shoes = new Chaussure;
        }

        $formAdminShoes = $this->createForm(ChaussureType::class, $shoes);
        $formAdminShoes->handleRequest($request);

        if($formAdminShoes->isSubmitted() && $formAdminShoes->isValid())
        {
            // dd($formAdminShoes);

            if($shoes->getId() )
                $txt = 'modifiée';
            else 
                $txt = 'enregistrée';
            
            //***Traitement Photos ***/ 
            $photo = $formAdminShoes->get('photo')->getData();
            $photo2 = $formAdminShoes->get('photo2')->getData();
            $photo3 = $formAdminShoes->get('photo3')->getData();
            $photo4 = $formAdminShoes->get('photo4')->getData();

            
            if($photo)
            {
                $nomOriginePhoto = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $securePhoto= $slugger->slug($nomOriginePhoto);
                $nouveauNomFichier = $securePhoto . '-' . uniqid() . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('photo_directory'), $nouveauNomFichier);
                $shoes->setPhoto($nouveauNomFichier);
                
            }
            if($photo2)
            {
                $nomOriginePhoto2 = pathinfo($photo2->getClientOriginalName(), PATHINFO_FILENAME);
                // $securePhoto2= $slugger->slug($nomOriginePhoto2);
                $nouveauNomFichier2 = $nomOriginePhoto2 . '-' . uniqid() . '.' . $photo2->guessExtension();
                $photo2->move($this->getParameter('photo_directory'), $nouveauNomFichier2);
                $shoes->setPhoto2($nouveauNomFichier2);
                
            }
            if($photo3)
            {
                $nomOriginePhoto3 = pathinfo($photo3->getClientOriginalName(), PATHINFO_FILENAME);
                // $securePhoto3= $slugger->slug($nomOriginePhoto3);
                $nouveauNomFichier3 = $nomOriginePhoto3 . '-' . uniqid() . '.' . $photo3->guessExtension();
                $photo3->move($this->getParameter('photo_directory'), $nouveauNomFichier3);
                $shoes->setPhoto3($nouveauNomFichier3);
                
            }
            if($photo4)
            {
                $nomOriginePhoto4 = pathinfo($photo4->getClientOriginalName(), PATHINFO_FILENAME);
                // $securePhoto4= $slugger->slug($nomOriginePhoto4);
                $nouveauNomFichier4 = $nomOriginePhoto4 . '-' . uniqid() . '.' . $photo4->guessExtension();
                $photo4->move($this->getParameter('photo_directory'), $nouveauNomFichier4);
                $shoes->setPhoto4($nouveauNomFichier4);
                
            }
            
            else 
            { 
                if(isset($photoEnregistree))
                    $shoes->setPhoto($photoEnregistree);
                else  
                    $shoes->setPhoto(null);

                if(isset($photoEnregistree2))
                    $shoes->setPhoto2($photoEnregistree2);
                else  
                    $shoes->setPhoto2(null);

                if(isset($photoEnregistree3))
                    $shoes->setPhoto3($photoEnregistree3);
                else  
                    $shoes->setPhoto3(null);

                if(isset($photoEnregistree4))
                    $shoes->setPhoto4($photoEnregistree4);
                else  
                    $shoes->setPhoto4(null);

            }
            //***FIN Traitement Photo ***/ 

            $manager->persist($shoes);
            $manager->flush();

            $this->addFlash('success', "La chaussure a été $txt avec succès.");

            return $this->redirectToRoute('backoffice_produit');
        }

        return $this->render('backoffice/admin_article_ajout.html.twig', [
            'formAdminShoes' => $formAdminShoes->createView(),
            'photoEnregistree' => $shoes->getPhoto(),
            'photoEnregistree2'=> $shoes->getPhoto2(),
            'photoEnregistree3'=> $shoes->getPhoto3(),
            'photoEnregistree4'=> $shoes->getPhoto4(), 
            'Modification' => $shoes->getId(),
        ]);
    }

/* ##################------------ FIN - CRUD - CHAUSSURE ------------################## */  







/* ################## DEBUT AFFICHAGE DES MESSAGES DE CONTACT ################## */ 

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
    public function userView(EntityManagerInterface $manager, UserRepository $repoUser, )
    {

        $colonnes = $manager->getclassMetadata(User::class)->getFieldNames();

        $cellules = $repoUser->findAll();

        return $this->render('backoffice/admin_user.html.twig', [
            'colonnes' => $colonnes,
            'cellules' => $cellules
        ]);
      
    }

   

    // #[Route('backoffice/user/{id}/update', name: 'app_admin_user_update')]
    // public function roleUser()
    // {


    //     return $this->render('backoffice/admin_user.html.twig');
    // }

    // ############################################ AFFICHAGE DES COMMENTAIRES #################################

    #[Route('backoffice/commentaire', name:'app_admin_commentaire')]
    #[Route('/backoffice/commentaire/{id}/delete', name:'app_admin_commentaire_delete')]
    public function adminCommentaire(CommentaireRepository $repoComment, EntityManagerInterface $manager, Commentaire $commentRemove=null): Response
    {
        $dataComment = $repoComment->findAll();
        $colonnes = $manager->getClassMetadata(Commentaire::class)->getFieldNames();

        if($commentRemove)
        {
            //On stock l'auteur du commentaire dans une variable afin de l'intégrer dans le message de validation
            $auteur = $commentRemove->getUser()->getNom()." ". $commentRemove->getUser()->getPrenom();
            $manager->remove($commentRemove);
            $manager->flush();
            $this->addFlash('success', "le commentaire de $auteur à bien été supprimé");
            return $this->redirectToRoute('app_admin_commentaire');
        }

        return $this->render('backoffice/admin_commentaire.html.twig', [
            'colonnes'=>$colonnes,
            'dataComment'=>$dataComment
        ]);

    }

    //############################################# MODIFICATION COMMENTAIRES #################################

    #[Route('/backoffice/commentaire/{id}/update', name:'app_admin_commentaire_update')]
    public function updateComment(Commentaire $commentaire, EntityManagerInterface $manager, Request $request): Response
    {
        $formComment = $this->createForm(CommentFormType::class, $commentaire);

        $formComment->handleRequest($request);

        if($formComment->isSubmitted() && $formComment->isValid())

        {
            $auteur = $commentaire->getUser()->getNom()." ". $commentaire->getUser()->getPrenom();
            $manager->persist($commentaire);
            $manager->flush();
            $this->addFlash('success', "le commentaire de $auteur à bien été modifié");
            return $this->redirectToRoute('app_admin_commentaire');
        }       
        

        return $this->render('backoffice/admin_commentaire_update.html.twig',[
            'formComment'=>$formComment->createView()
        ]);

    }

}
