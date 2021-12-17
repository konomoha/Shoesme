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
            
            $this->addFlash('success', "Félicitations, vous êtes inscrit(e)!");

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
        $profilUpdate = $this->createForm(RegistrationFormType::class, $user, ['userUpdate'=>true]);
        $profilUpdate->handleRequest($request);
        
            $avatarActuel = $user->getAvatar();
        

        if($profilUpdate->isSubmitted() && $profilUpdate->isValid())
        {
            // dd($user);
            $avatar = $profilUpdate->get('avatar')->getData();

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
            
            else
            {
                if(isset($avatarActuel))
                    $user->setAvatar($avatarActuel);

            else{
                $user->setAvatar("test");
            }
               
            }
            
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vous avez modifié vos informations, merci de vous authentifier à nouveau');

            return $this->redirectToRoute('app_logout');
        }
        return $this->render('registration/profil_edit.html.twig', [
            'profilUpdate'=> $profilUpdate->createView(),
            'avatarActuel'=>$avatarActuel]);
    }

    
}
