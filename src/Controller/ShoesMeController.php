<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Chaussure;
use App\Entity\Commentaire;
use App\Form\CommentFormType;
use App\Form\ContactFormType;

use App\Repository\CommentaireRepository;

use App\Repository\ChaussureRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class ShoesMeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ChaussureRepository $chaussureRepo): Response
    {
            
        
        
        $shoes_m = $chaussureRepo->findBy(
            array('sexe' => 'm'), // condition where
            array (), //order by
            5, // la limite de chaussures à afficher
            0); // offset
        
        
        $shoes_f = $chaussureRepo->findBy(
            array('sexe' => 'f'), // condition where
            array (), //order by
            5, // la limite de chaussures à afficher
            0); // offset

        $shoes = $chaussureRepo->findBy(
            array(), // condition where
            array (), //order by
            5, // la limite de chaussures à afficher
            0); // offset;
        
        return $this->render('shoes_me/home.html.twig', [
            'chaussure_m' => $shoes_m,
            'chaussure_f' => $shoes_f,
            'chaussure' => $shoes,
        ]);



    }



    #[Route ('/contact', name: 'contact')]
    public function contact(Contact $contact = null, Request $request, EntityManagerInterface $manager): Response
    {

        $contact = new Contact;

        $formContact = $this->createForm(ContactFormType::class, $contact);

        $formContact->handleRequest($request);

        if($formContact->isSubmitted() && $formContact->isValid())
        {


            $contact->setDate(new \DateTime());
            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('home');

        }


        return $this->render('shoes_me/contact.html.twig', [
            'formContact' => $formContact->createView()
        ]);
    }

    #[Route ('/mentions_legales', name: 'm_l')]
    public function ML()
    {
        return $this->render ('shoes_me/mentions.legales.html.twig');
    }   
}