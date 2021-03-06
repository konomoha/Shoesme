<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\PhotoType;
use App\Entity\Chaussure;
use App\Entity\Commentaire;
use App\Form\ChaussureType;
use App\Form\Chaussure3Type;
use App\Form\CommentFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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

/* ################## ROUTE AFFICHAGE ET SUPPRESSION CHAUSSURE ################## */
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
            $id=$shoesRemove->getId()
            ;
            $manager->remove($shoesRemove);
            $manager->flush();
            $this->addFlash('suppression', "La chaussure n?? $id a ??t?? supprim??e");

            return $this->redirectToRoute('backoffice_produit');
        }
        //Fin suppression chaussure

        return $this->render('backoffice/admin_article.html.twig', [
            'colonneChaussure'=>$titreColonneChaussure,
            'chaussure'=>$shoes
        ]);
    }


/* ################## ROUTE AJOUT ET MODIFICATION CHAUSSURE ################## */
    #[Route('/backoffice/produit/ajout', name: 'backoffice_produit_ajout')]
    #[Route('/backoffice/produit/modification/{id}', name: 'backoffice_produit_modification')]
    public function backOfficeProduitForm(Chaussure $shoes=null, Request $request,EntityManagerInterface $manager, SluggerInterface $slugger, ChaussureRepository $repoChaussure)
    {
        $titreColonneChaussure=$manager->getClassMetadata(Chaussure::class)->getFieldNames();

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
                $txt = 'modifi??e';
            else 
                $txt = 'enregistr??e';
            
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

            $this->addFlash('success', "La chaussure a ??t?? $txt avec succ??s.");

            return $this->redirectToRoute('backoffice_produit');
        }

       //R??cup??ration et affichage du model s??lectionn??
        $model=$request->query->get('model');
        $selecteurModel='';
        if($test)
        {
            $selecteurModel= $repoChaussure->findBy(['model'=>$model]); 
        } 

        $chaussure=$repoChaussure->findAll();
    

        return $this->render('backoffice/admin_article_ajout.html.twig', [
            'formAdminShoes' => $formAdminShoes->createView(),
            'photoEnregistree' => $shoes->getPhoto(),
            'photoEnregistree2'=> $shoes->getPhoto2(),
            'photoEnregistree3'=> $shoes->getPhoto3(),
            'photoEnregistree4'=> $shoes->getPhoto4(), 
            'Modification' => $shoes->getId(),
            'Chaussure'=>$chaussure,
            'selecteurModel'=>$selecteurModel,
            'colonneChaussure'=>$titreColonneChaussure,
        ]);
    }




/* ################## Affichage de toute la BDD ################## */
#[Route('backoffice/affichage', name: 'backoffice_affichage_general')]
public function backOfficeAffihageGeneral(ChaussureRepository $shoesRepo, EntityManagerInterface $manager, Request $request):Response
{
    $selecteurTab=[];
    $hidden="hidden";
    //R??cup??ration des titres des champs
    $titreColonneShoes=$manager->getClassMetadata(Chaussure::class)->getFieldNames();
    
    //Cr??ation du tableau pour le selecteur
    $selecteurProduit=$shoesRepo->findAll();
    foreach($selecteurProduit as $key=>$value)
    {
        if( $value->getModel()!=NULL && !(in_array(['marque'=>$value->getMarque(), 'model'=>$value->getModel()], $selecteurTab)) )
        {
            $selecteurTab[]=[
                'marque'=> $value->getMarque(),
                'model'=> $value->getModel(),
            ];    
        }
    }
    
    //Affichage article s??lectionn?? dans le selecteur produit ou global
    if($request->get('produit'))
    {
        $shoes = $shoesRepo->findBy(['model'=>$request->get('produit')], ['marque'=>'ASC','model'=>'ASC']);

        $hidden ='';
    }
    else
    {
        $shoes = $shoesRepo->findAll();
    }

    return $this->render('backoffice/admin_affichage.html.twig', [
        'titreColonne'=> $titreColonneShoes,
        'chaussure'=> $shoes,
        'hidden'=>$hidden,
        'selecteur'=>$selecteurTab,
    ]);
}


/* ################## Affichage d'un article avec toutes les couleurs et pointures + modification affichage et stock ################## */
#[Route ('backoffice/affichage/article/{id}', name:'backoffice_affichage_article')]
#[Route('/backoffice/affichage/modification/{id}', name: 'backoffice_produit_modification')]
public function backofficeAffichageArticle (ChaussureRepository $shoesRepo, SluggerInterface $slugger, EntityManagerInterface $manager, Request $request=null, Chaussure $shoes):Response
{
    $titreColonneShoes=$manager->getClassMetadata(Chaussure::class)->getFieldNames();   

    $couleur=[];//on d??clare couleur ?? vide pour pouvoir l'envoyer au template.
    $pointure=[];//idem pour les pointures
    $sexe=[];//idem pour le genre
    $adressePhoto=[];//idem pour les photos
    $model='';
    $stocktotal=0;//calcul du stock total pour un model
    $element=[];//variable de stockage
    $hidden='hidden';//permet de masquer les formulaires de modification
    
    
    //on v??rifie que l'url contient 'modification', si oui on change l'affichage
    if(stristr($request->getPathInfo(), "modification"))
    {
        $hidden='';
        //Si on recup??re les donn??es de request
        if($request->request->all())
        { 
           $data=$request->request->all();
            
           foreach($data as $key=>$value)
           {
               //Si on r??cup??re un num??ric, la $key correspond ?? l'id de la chaussure, on a re??u des stock ?? modifier
               if(is_numeric($key))
               {
                    if($value)
                    {
                    $stock=(int)$value;
                    $shoesChangementStock=$shoesRepo->find($key);
                    $shoesChangementStock->setStock($stock);
                    $manager->persist($shoesChangementStock);
                    }
               }
               //Sinon on re??oit une marque, un model, une couleur et la valeur du champs affichage
               else
               {
                    //D??coupage de la $key et r??cup??ration de la marque en 0, du model en 1, et de la couleur en 2
                    $modelChangementAffichage=str_replace('_', ' ',explode('/',$key,)); 

                    $shoesChangementAffichage=$shoesRepo->findBy(['marque'=>$modelChangementAffichage[0], 'model'=>$modelChangementAffichage[1], 'couleur'=>$modelChangementAffichage[2]]);
                        //Pour toutes les chaussures correspondantes, on modifie l'affichage
                        foreach($shoesChangementAffichage as $enregistrement)
                        {
                            $enregistrement->setAffichage($value);
                            $manager->persist($enregistrement);
                        }   
               } 
           }
           $manager->flush();
        }      
    }

/* ########################## AFFICHAGE */

    //On r??cup??re tous les enregistrements de chaussure correspondant au model s??lectionn??
    $shoesModel = $shoesRepo->findBy(['model'=>$shoes->getModel()], ['couleur'=>'ASC', 'pointure'=>'ASC']); 
    
    //On r??cup??re les couleurs, les pointures et les stocks disponibles pour le model s??lectionn??.
    foreach($shoesModel as $key=>$value)
    {
        $stocktotal+=$value->getStock();
        
        //Si la pointure n'est pas nulle et qu'elle n'est pas d??j?? dans le tableau on l'ajoute.
       if($value->getPointure()!=NULL && !(in_array($value->getPointure(), $pointure)))
       {
           $pointure[]=$value->getPointure();
       }

        //Si la valeur de la couleur n'est pas nulle et qu'elle n'est pas d??j?? dans le tableau couleur alors on l'ajoute au tableau.
        if($value->getCouleur()!=NULL && !(in_array($value->getCouleur(), $couleur)) )
        {
            $couleur[]=$value->getCouleur();  
        }

        //Ici on stocke les genres disponibles pour ce mod??le de chaussure.
        if($value->getSexe()!=NULL)
        {
            $genre=$value->getSexe();  
            switch($genre)
            {
                case 'm':
                    if( !(in_array("homme", $sexe)) )
                    $sexe[]="homme";
                    break;
                case 'f':
                    if( !(in_array("femme", $sexe)) )
                    $sexe[]="femme";
                    break;
                case 'g':
                    if( !(in_array("gar??on", $sexe)) )
                    $sexe[]="gar??on";
                    break;
                case 'fille':
                    if( !(in_array("fille", $sexe)) )
                    $sexe[]="fille";
                    break;
            }
            
        }
        $model=$value->getModel();
    }
    //R??cup??ration de l'adresse des photos : 
    foreach ($couleur as $value)
    {
        $element[]=$shoesRepo->findOneBy(['model'=>$model,'couleur'=>$value]);
    }
    
    if($element)
    {
        foreach ($element as $key=>$value)
        {
            
        $adressePhoto[]=[
            'couleur'=>$value->getCouleur(),
            'photo'=>$value->getPhoto(),
            'photo2'=>$value->getPhoto2(),
            'photo3'=>$value->getPhoto3(),
            'photo4'=>$value->getPhoto4()
        ];
        }
    }
    
    //on r??cup??re le nombre de couleur disponible
    $nbcouleur=count($couleur);
    $nbpointure=count($pointure);

    return $this->render('backoffice/admin_affichage_article.html.twig', [
        'chaussure'=>$shoesModel,
        'titreColonne'=> $titreColonneShoes,
        'couleur'=>$couleur,
        'nbcouleur'=>$nbcouleur,
        'stockTotal'=>$stocktotal,
        'pointure'=>$pointure,
        'nbpointure'=>$nbpointure,
        'sexe'=>$sexe,
        'photo'=>$adressePhoto,
        'hidden'=>$hidden,
        
    ]);
}

/* ################## Cr??ation Articles multiples couleurs, multiples pointures ################## */
#[Route('/backoffice/produit/ajout', name: 'backoffice_produit_ajout')]
public function backOfficeAjoutArticle(Request $request,EntityManagerInterface $manager, SluggerInterface $slugger, ChaussureRepository $repoChaussure)
{
    $compteur=0; //permet de compter le nombre d'enregistrement cr????
    

    $shoesForm= $this->createForm(Chaussure3Type::class);//formulaire fait main pour pouvoir boucler sur les couleurs et les pointures et cr??er les enregistrements li??s.
    $shoesForm->handleRequest($request);
    
    if($shoesForm->isSubmitted() && $shoesForm->isValid())
    {  
        $data=$shoesForm->getData();
        // dd($data);
        if($shoesForm->get('photo'))
        {
            $photo = $shoesForm->get('photo')->getData();
            $nomOriginePhoto = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $securePhoto= $slugger->slug($nomOriginePhoto);
            $nouveauNomFichier = $data['marque'].'-'.$data['model'].'-'.$securePhoto. '.' .$photo->guessExtension();
            $photo->move($this->getParameter('photo_directory'), $nouveauNomFichier);
        }
        if($shoesForm->get('photo2'))
        {
            $photo2 = $shoesForm->get('photo2')->getData();
            $nomOriginePhoto2 = pathinfo($photo2->getClientOriginalName(), PATHINFO_FILENAME);
            $securePhoto2= $slugger->slug($nomOriginePhoto2);
            $nouveauNomFichier2 = $data['marque'].'-'.$data['model'].'-'.$securePhoto2. '.' . $photo2->guessExtension();
            $photo2->move($this->getParameter('photo_directory'), $nouveauNomFichier2);
        }
        if($shoesForm->get('photo3'))
        {
            $photo3 = $shoesForm->get('photo3')->getData();
            $nomOriginePhoto3 = pathinfo($photo3->getClientOriginalName(), PATHINFO_FILENAME);
            $securePhoto3= $slugger->slug($nomOriginePhoto3);
            $nouveauNomFichier3 = $data['marque'].'-'.$data['model'].'-'.$securePhoto3. '.' . $photo3->guessExtension();
            $photo3->move($this->getParameter('photo_directory'), $nouveauNomFichier3);
        }
        if($shoesForm->get('photo4'))
        {
            $photo4 = $shoesForm->get('photo4')->getData();
            $nomOriginePhoto4 = pathinfo($photo4->getClientOriginalName(), PATHINFO_FILENAME);
            $securePhoto4= $slugger->slug($nomOriginePhoto4);
            $nouveauNomFichier4 = $data['marque'].'-'.$data['model'].'-'.$securePhoto4. '.' . $photo4->guessExtension();
            $photo4->move($this->getParameter('photo_directory'), $nouveauNomFichier4);

        }

        foreach($data['couleur'] as $couleur)
        {
            foreach($data['pointure'] as $pointure)   
            {
                $shoes = new Chaussure;

                $shoes->setCouleur($couleur);
                $shoes->setPointure($pointure);
                $shoes->setType($data['type']);
                $shoes->setMarque($data['marque']);
                $shoes->setModel($data['model']);
                $shoes->setSexe($data['sexe']);
                $shoes->setAffichage($data['affichage']);
                $shoes->setMatiere($data['matiere']);
                $shoes->setDescriptif($data['descriptif']);
                $shoes->setPrix($data['prix']);
                $shoes->setMatiere($data['matiere']);
                $shoes->setStock($data['stock']);

                
                if($photo)
                {
                    $shoes->setPhoto($nouveauNomFichier);   
                }
                if($photo2)
                {
                    $shoes->setPhoto2($nouveauNomFichier2);
                }
                if($photo3)
                {
                    $shoes->setPhoto3($nouveauNomFichier3);
                }
                if($photo4)
                {
                    $shoes->setPhoto4($nouveauNomFichier4);
                }
                
                ++$compteur;
                $manager->persist($shoes);
                $manager->flush();
            }
        }
        
        $this->addFlash('success', "$compteur articles ajout??s avec succ??s.");

        return $this->redirectToRoute('backoffice_affichage_general');
    }

    return $this->render('backoffice/ajout_article.html.twig', [
        'shoesForm' => $shoesForm->createView(),
    ]);
}

/* ################## Modification Photo ################## */
#[Route('/backoffice/affichage/photo/modification', name: 'backoffice_affichage_photo_modification')]
public function backOfficeModificationPhoto(ChaussureRepository $shoesRepo, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger):Response
{
    $shoesAffichage='';
    $modelCouleur=[];
    $selecteurTab=[];
    $compteur=0;
    $photoUpdateForm = NULL;
    $test='Coucou';
    

    //Cr??ation des valeurs pour le selecteurs du template
    $selecteurShoes=$shoesRepo->findAll();
    foreach($selecteurShoes as $key=>$value)
    {
        if( $value->getModel()!=NULL && !(in_array(['marque'=>$value->getMarque(), 'model'=>$value->getModel(), 'couleur'=>$value->getCouleur()], $selecteurTab)) )
        {
            $selecteurTab[]=[
                'marque'=> $value->getMarque(),
                'model'=> $value->getModel(),
                'couleur'=> $value->getCouleur()
            ];    
        }
    }
    
    
   
    if($request->query->get('selecteurChaussure'))
    {
        //R??cup??ration : 0 => la marque, 1 => le model, 2 => la couleur
        $modelCouleur=str_replace('_', ' ',explode('/',$request->query->get('selecteurChaussure')));
        
        //On r??cup??re un enregistrement pour afficher les photos sur le template
        $shoesAffichage=$shoesRepo->findOneBy(['marque'=>$modelCouleur[0], 'model'=>$modelCouleur[1], 'couleur'=>$modelCouleur[2]]);
        
        //On r??cup??re tous les enregistrements correspondant ?? modifier
        $shoesUpdate=$shoesRepo->findBy(['marque'=>$modelCouleur[0], 'model'=>$modelCouleur[1], 'couleur'=>$modelCouleur[2]]);
    
    
        $photoUpdateForm= $this->createForm(PhotoType::class);
        $photoUpdateForm->handleRequest($request);
        if($photoUpdateForm->isSubmitted() && $photoUpdateForm->isValid())
        {
            
            foreach($shoesUpdate as $key=>$value)
            {
                
                $photoEnregistree = $value->getPhoto();
                $photoEnregistree2= $value->getPhoto2();
                $photoEnregistree3= $value->getPhoto3();
                $photoEnregistree4= $value->getPhoto4();   

                $photo  = $photoUpdateForm->get('photo')->getData();
                $photo2 = $photoUpdateForm->get('photo2')->getData();
                $photo3 = $photoUpdateForm->get('photo3')->getData();
                $photo4 = $photoUpdateForm->get('photo4')->getData();

            
                if($photo)
                {
                    
                    $nomOriginePhoto = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $securePhoto= $slugger->slug($nomOriginePhoto);
                    $nouveauNomFichier = $value->getMarque().'-'.$value->getModel().'-'.$value->getCouleur().'-'.$securePhoto .'.' . $photo->guessExtension();
                    $photo->move($this->getParameter('photo_directory'), $nouveauNomFichier);
                    $value->setPhoto($nouveauNomFichier);
                    
                }
                if($photo2)
                {
                    $nomOriginePhoto2 = pathinfo($photo2->getClientOriginalName(), PATHINFO_FILENAME);
                    $securePhoto2= $slugger->slug($nomOriginePhoto2);
                    $nouveauNomFichier2 = $value->getMarque().'-'.$value->getModel().'-'.$value->getCouleur().'-'.$securePhoto .'.' . $photo->guessExtension();
                    $photo2->move($this->getParameter('photo_directory'), $nouveauNomFichier2);
                    $value->setPhoto2($nouveauNomFichier2);
                    
                }
                if($photo3)
                {
                    $nomOriginePhoto3 = pathinfo($photo3->getClientOriginalName(), PATHINFO_FILENAME);
                    $securePhoto3= $slugger->slug($nomOriginePhoto3);
                    $nouveauNomFichier3 = $value->getMarque().'-'.$value->getModel().'-'.$value->getCouleur().'-'.$securePhoto .'.' . $photo->guessExtension();
                    $photo3->move($this->getParameter('photo_directory'), $nouveauNomFichier3);
                    $value->setPhoto3($nouveauNomFichier3);
                    
                }
                if($photo4)
                {
                    $nomOriginePhoto4 = pathinfo($photo4->getClientOriginalName(), PATHINFO_FILENAME);
                    $securePhoto4= $slugger->slug($nomOriginePhoto4);
                    $nouveauNomFichier4 = $value->getMarque().'-'.$value->getModel().'-'.$value->getCouleur().'-'.$securePhoto .'.' . $photo->guessExtension();
                    $photo4->move($this->getParameter('photo_directory'), $nouveauNomFichier4);
                    $value->setPhoto4($nouveauNomFichier4);     
                }
            
            
                else 
                { 
                    if(isset($photoEnregistree))
                        $value->setPhoto($photoEnregistree);
                    else  
                        $value->setPhoto(null);

                    if(isset($photoEnregistree2))
                        $value->setPhoto2($photoEnregistree2);
                    else  
                        $value->setPhoto2(null);

                    if(isset($photoEnregistree3))
                        $value->setPhoto3($photoEnregistree3);
                    else  
                        $value->setPhoto3(null);

                    if(isset($photoEnregistree4))
                        $value->setPhoto4($photoEnregistree4);
                    else  
                        $value->setPhoto4(null);

                }
                ++$compteur;
                $manager->persist($shoes);
            }
            $manager->flush();
            $this->addFlash('success', "$compteur chaussures ont ??t?? mises ?? jour");
            return $this->redirectToRoute('backoffice_affichage_photo_modification');
        }
                    
    }

       
    return $this->render('backoffice/admin_affichage_photo_modification.html.twig', [
        'selecteur'=>$selecteurTab,
        'AffichagePhoto'=>$shoesAffichage,
        'formPhoto'=>($photoUpdateForm) ? $photoUpdateForm->createView() : $photoUpdateForm='' 
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

            $this->addFlash('success', "Le commentaire $id a bien ??t?? supprimer avec succ??s");

            return $this->redirectToRoute('app_message');
       
    }


    // ############################################ AFFICHAGE DES COMMENTAIRES #################################

    #[Route('backoffice/commentaire', name:'app_admin_commentaire')]
    #[Route('/backoffice/commentaire/{id}/delete', name:'app_admin_commentaire_delete')]
    public function adminCommentaire(CommentaireRepository $repoComment, EntityManagerInterface $manager, Commentaire $commentRemove=null): Response
    {
        $dataComment = $repoComment->findAll();
        $colonnes = $manager->getClassMetadata(Commentaire::class)->getFieldNames();

        if($commentRemove)
        {
            //On stock l'auteur du commentaire dans une variable afin de l'int??grer dans le message de validation
            $auteur = $commentRemove->getUser()->getNom()." ". $commentRemove->getUser()->getPrenom();
            $manager->remove($commentRemove);
            $manager->flush();
            $this->addFlash('success', "le commentaire de $auteur ?? bien ??t?? supprim??");
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
            $this->addFlash('success', "le commentaire de $auteur ?? bien ??t?? modifi??");
            return $this->redirectToRoute('app_admin_commentaire');
        }       
        

        return $this->render('backoffice/admin_commentaire_update.html.twig',[
            'formComment'=>$formComment->createView()
        ]);

    }

}
