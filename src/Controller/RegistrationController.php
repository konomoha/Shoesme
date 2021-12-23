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
        $user = new User();
        $registerForm = $this->createForm(RegistrationFormType::class, $user, ['userRegister'=>true]);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) 
        {

            $avatar = $registerForm->get('avatar')->getData();
            
           
            $hash = $userPasswordHasher->hashPassword(
                $user,
                $registerForm->get('password')->getData()
            );

            $user->setPassword($hash);

            if($avatar)
            {   
                $nomOrigineAvatar = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);           
                $secureNomAvatar = $slugger->slug($nomOrigineAvatar);       
                $nouveauNomFichier = $secureNomAvatar . '-' . uniqid(). '.' .$avatar->guessExtension();

     
                $avatar->move(
                    $this->getparameter('avatar_directory'),
                    $nouveauNomFichier);

                $user->setAvatar($nouveauNomFichier);
              
            }
            
            $this->addFlash('success_register', "Félicitations, vous êtes inscrit(e)!");

            $entityManager->persist($user);
            $entityManager->flush();
            

            return $this->redirectToRoute('home');
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
        
        if($user)
        {
            $avatarActuel = $user->getAvatar();
        }
            
        $profilUpdate = $this->createForm(RegistrationFormType::class, $user, ['userUpdate'=>true]);
        $profilUpdate->handleRequest($request);

        if($profilUpdate->isSubmitted() && $profilUpdate->isValid())
        {
            // dd($user);
            
            $avatar = $profilUpdate->get('avatar')->getData();

            if($avatar)
            {   
                $nomOrigineAvatar = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);           
                $secureNomAvatar = $slugger->slug($nomOrigineAvatar);       
                $nouveauNomFichier = $secureNomAvatar . '-' . uniqid(). '.' .$avatar->guessExtension();

                try
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
            
            else
            {
                if(isset($avatarActuel))
                {
                    $user->setAvatar($avatarActuel);
                }
                    

                else
                {
                    $user->setAvatar(null);
                }
       
            }

            $this->addFlash('success_update', "Votre profil a été mis à jour, veuillez vous authentifier à nouveau.");
            $manager->persist($user);
            $manager->flush();
            
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
