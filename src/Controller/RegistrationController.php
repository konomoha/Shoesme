<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegistrationFormType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $avatar = $registerForm->get('avatar')->getData();
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $registerForm->get('plainPassword')->getData()
                )
            );

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
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $registerForm->createView(),
        ]);
    }
}
