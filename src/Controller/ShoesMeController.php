<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Chaussure;
use App\Entity\Commentaire;
use App\Form\CommentFormType;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class ShoesMeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        return $this->render('shoes_me/home.html.twig');

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

    #[Route('/details_article/{id}', name:'details_article')]
    public function detailArticle(Request $request, EntityManagerInterface $manager, Chaussure $chaussure):Response
    {
        $commentaire = new Commentaire;
        
        $id = $this->getUser();
        $formComment = $this->createForm(CommentFormType::class, $commentaire);

        $formComment->handleRequest($request);
       
        if($formComment->isSubmitted() && $formComment->isValid())
        {

            // dd($id);
            $commentaire->setDate(new \DateTime());
            // $commentaire->setUser($id);
            $manager->persist($commentaire);
            $manager->flush();
            $this->addFlash('success', "Félicitations! Votre commentaire a bien été posté!");
            return $this->redirectToRoute('details_article', ['id'=> $chaussure->getId()]);
        }

        return $this->render('details_articles/details_articles.html.twig', [
            'formComment' => $formComment->createView()
        ]);

    }
    
}

