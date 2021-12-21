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
    public function index( ChaussureRepository $repoChaussure): Response
    {

        $chaussure = $repoChaussure->findBy(
            array(), // condition where
            array (), //order by
            10, // la limite de chaussures à afficher
            0); // offset

        return $this->render('shoes_me/home.html.twig', [
            'chaussure'=> $chaussure
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

    #[Route('/details_article/{id}', name:'details_article')]
    public function detailArticle(CommentaireRepository $repoCommentaire, Request $request, EntityManagerInterface $manager, Chaussure $chaussure):Response
    {
        $commentaire = new Commentaire;
        $total= 0;
        foreach($chaussure->getCommentaires() as $key => $value)
        {
            if(!empty($value->getEvaluation()))
            {
                $note = $value->getEvaluation();
                // dump($eval);
                $dataNote[] = $note;
                // dump($evaluation);
                $total += $note;
            }
        }

        dump(count($dataNote));

        $resultat = $total / count($dataNote);
        $moyenne = round($resultat,1);

        // dump($moyenne);

        // dump($chaussure->getCommentaires());
        
        $id = $this->getUser();
        // $idshoes = $chaussure;
        $formComment = $this->createForm(CommentFormType::class, $commentaire);
        $formComment->handleRequest($request);
        $datacom = $repoCommentaire->findAll();
        $total = 0;

        if($formComment->isSubmitted() && $formComment->isValid())
        {
             
            $commentaire->setDate(new \DateTime());
            $commentaire->setUser($id);
            $commentaire->setChaussure($chaussure);
            $manager->persist($commentaire);
            $manager->flush();
            $this->addFlash('success_comment', "Félicitations! Votre commentaire a bien été posté!");
            return $this->redirectToRoute('details_article', ['id'=> $chaussure->getId()]);
        }
        
        // foreach($datacom as $note)
        // {
        //     $total += $note->getEvaluation();
        // }

        return $this->render('details_articles/details_articles.html.twig', [
            'formComment' => $formComment->createView(),
            'chaussure'=> $chaussure,
            'datacom'=>$datacom,
            'moyenne'=>$moyenne
        ]);

    }
}