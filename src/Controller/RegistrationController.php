<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if(!$this->getUser())
        {

        
            //nouvel objet issu de la classe User
            $user = new User();

            //On déclare ici une variable $registerForm dans laquelle on se sert de la méthode createForm dans laquelle on fait appel à un formulaire spécifique issu de la classe RegistrationFormType en usant la condition true.
            $registerForm = $this->createForm(RegistrationFormType::class, $user, ['userRegister'=>true]);

            $registerForm->handleRequest($request);

            if ($registerForm->isSubmitted() && $registerForm->isValid()) 
            {

                $avatar = $registerForm->get('avatar')->getData();

                // on encore le mot de passe en faisant appel à l'objet $userPasswordHasher de l'interface UserPasswordHasherInterface
                //En argument on lui fournit l'objet entité dans lequel nous allons encoder un élément ($user) et on lui fournit le mot de passe saisi dans le formulaire à encoder
            
                $hash = $userPasswordHasher->hashPassword(
                    $user,
                    $registerForm->get('password')->getData()
                );

                // On transmet au setter du passwordf la clé de hachage à enregistrer en BDD
                $user->setPassword($hash);

                //Si l'utilisateur a choisi un avatar, on débute le traitement et la sécurisation du nom de l'image
                if($avatar)
                {   
                    //On récupère le nom d'origine de l'avatar choisi
                    $nomOrigineAvatar = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME); 
                    
                    //On sécurise l'inclusion du nom du fichier dans l'URL
                    $secureNomAvatar = $slugger->slug($nomOrigineAvatar);       
                    $nouveauNomFichier = $secureNomAvatar . '-' . uniqid(). '.' .$avatar->guessExtension();

                    // On copie l'image vers le bon chemin, vers le bon dossier 'public/avatar' (services.yaml)
                    $avatar->move(
                        $this->getparameter('avatar_directory'),
                        $nouveauNomFichier);
                    
                    //On transmet ensuite au setteur le nouveau nom du fichier
                    $user->setAvatar($nouveauNomFichier);
                
                }
                
                $this->addFlash('success_register', "Félicitations, vous êtes inscrit(e)!");

                $entityManager->persist($user);
                $entityManager->flush();
                
                //On redirige l'internaute sur la page d'accueil
                return $this->redirectToRoute('home');
            }
        }

        else
        {
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $registerForm->createView(),
        ]);
    }

    ///////////////////////////CREATION D'UNE ROUTE + TEMPLATE PROFIL (profil.html.twig)\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        
        if(!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }
        
        $user = $this->getUser();

        return $this->render('registration/profil.html.twig', [
            'profildata'=> $user ]);

    }

    //Route edit mise à part de la route app_profil pour plus de clarté 

    #[Route('/profil/{id}/edit', name: 'app_profil_edit')]
    public function profilEdit(User $user=null, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger):Response
    {
        //On vérifie que l'utilisateur est bien enregistré en bdd. Si oui, on stock son avatar dans une variable
        if($user)
        {
            $avatarActuel = $user->getAvatar();
        }

        //On fait appel au formulaire dédié à la modification en passant en 'true' le FormType userUpdate    
        $profilUpdate = $this->createForm(RegistrationFormType::class, $user, ['userUpdate'=>true]);
        $profilUpdate->handleRequest($request);

        if($profilUpdate->isSubmitted() && $profilUpdate->isValid())
        {
            // dd($user);
            
            //On récupère toutes les informations de l'image uploadé dans le formulaire
            $avatar = $profilUpdate->get('avatar')->getData();

            if($avatar)//si une photo est uploadé dans le formulaire, on entre le IF et on traite l'image
            {   
                $nomOrigineAvatar = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);           
                $secureNomAvatar = $slugger->slug($nomOrigineAvatar);       
                $nouveauNomFichier = $secureNomAvatar . '-' . uniqid(). '.' .$avatar->guessExtension();

                try //tentative de copie de l'image dans le dossier avatar/
                {
                    $avatar->move(
                    $this->getparameter('avatar_directory'),
                    $nouveauNomFichier);
                }

                catch(FileException $e)
                {
                    
                }
                
                $user->setAvatar($nouveauNomFichier);
              
            }
            
            //Sinon, aucune image n'a été uploadée, on renvoie dans la bdd l'avatar actuel
            else
            {
                //Si l'image actuelle est définie en BDD, alors en cas de modification, si on ne change pas de photo, on réinsère la photo actuelle en bdd
                if(isset($avatarActuel))
                {
                    $user->setAvatar($avatarActuel);
                }
                    
                //Sinon aucune image n'a été uploadée, on envoie la valeur NULL en BDD 
                else
                {
                    $user->setAvatar(null);
                }
       
            }

            $this->addFlash('success_update', "Votre profil a été mis à jour, veuillez vous authentifier à nouveau.");
            $manager->persist($user);
            $manager->flush();
            
            //On redirige finalement l'internaute vers la page de connexion
            return $this->redirectToRoute('app_logout');

        }
        return $this->render('registration/profil_edit.html.twig', [
            'profilUpdate'=> $profilUpdate->createView(),
            'avatarActuel'=>$user->getAvatar()]);
    }


/* ############################################ GESTION UTILISATEUR BO ############################################ */  
    #[Route('backoffice/user/{id}/Supprimer', name: 'app_admin_user_remove')]
    #[Route('backoffice/user/{id}/update', name: 'app_admin_user_update')]
    #[Route('backoffice/user', name: 'app_admin_user')]
    public function userView(EntityManagerInterface $manager, UserRepository $repoUser, User $user=null, Request $request, RegistrationFormType $userFormBack )
    {
        $userFormBack="";
        if($user)
        {
            if($request->query->get('op') == 'roleUpdate')
            {
                

                $userFormBack = $this->createForm(RegistrationFormType::class, $user, ['roleUpdate'=>true]);
                // dd($userFormBack);

                $userFormBack->handleRequest($request);

                if($userFormBack->isSubmitted() //&& $userFormBack->isValid() 
                )
                {
                    $infos=$user->getPrenom().' '.$user->getNom();

                    $manager->persist($user);
                    $manager->flush();

                    $this->addFlash('success', "L'utilisateur $infos est maintnenant un admin beau gosse !");

                    return $this->redirectToRoute('app_admin_user');
                }
                
            }
            elseif ($request->query->get('op')=='supprimer')
            {
                $utilisateur=$user->getPrenom().' '.$user->getNom();
                $manager->remove($user);
                $manager->flush();

                $this->addFlash('success', "Ce n'est qu'un au-revoir $utilisateur !");
                
                return $this->redirectToRoute('app_admin_user');
            }
        }
            
        //Récupération et affichage des infos pour le tableau d'affichage
        $colonnes = $manager->getclassMetadata(User::class)->getFieldNames();
        $cellules = $repoUser->findAll();

        return $this->render('backoffice/admin_user.html.twig', [
            'colonnes' => $colonnes,
            'cellules' => $cellules,
            'formulaire' => ($request->query->get('op')=='roleUpdate') ? $userFormBack->createView() : '', 
            
        ]);
      
    }

   

    
}
