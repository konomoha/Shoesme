<?php

namespace App\Controller;

use App\Entity\Chaussure;
use App\Entity\Commentaire;
use App\Form\CommentFormType;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetailsArticlesController extends AbstractController
{

    #[Route('/details_article/{id}', name:'details_article')]
    public function detailArticle(CommentaireRepository $repoCommentaire, Request $request, EntityManagerInterface $manager, Chaussure $chaussure, ChaussureRepository $chaussureRepo):Response
    {
        //1er filtrage : on sélectionne le modèle de la chaussure grâce à l'id passé dans l'URL
        $model = $chaussure->getModel();

        //A partir du modèle on fait une epremière recherche de toutes les chaussures correspondantes, on stock le résultat dans dataChaussure et on le transmet au template details_article
        $dataChaussure = $chaussureRepo->findBy([
                    'model'=>$model
                    // 'pointure'=>$pointure,
                    // 'couleur'=>$couleur
                    ],
                    
            
                );
        
        $sizeGroup = $chaussureRepo->findSize($model);

        //2eme filtrage : On récupère la valeur de l'indice 'pointure' passé dans l'URL via un formulaire en méthode 'get'
        $pointure=$request->query->get('pointure');

        //On effectue une seconde recherche du même modèle de chaussure mais en appliquant une taille spécifique. Le résultat est stocké dans'tailleChaussure' puis transmit au template 'details_article'
        $tailleChaussure = $chaussureRepo->findBy([
            'model'=>$model,
            'pointure'=>$pointure
        ]);
        
        //3ème filtrage : On récupère la valeur de l'indice 'couleur' passé dans l'URL via un formulaire en méthode 'get'
        $couleur=$request->query->get('couleur');

        //On effectue une troisième recherche du même modèle de chaussure mais ajoutant à la taille sélectionnée auparavant une couleur spécifique. Le résultat est stocké dans 'couleurChaussure' puis transmit au template 'details_articles'
        $couleurChaussure = $chaussureRepo->findBy([
            'couleur'=>$couleur,
            'pointure'=>$pointure,
            'model'=>$model
        ]);
       
        $commentaire = new Commentaire;
        $total= 0;
        $dataNote = [];
        $moyenne=0;
        $resultat=0;
       
            foreach($chaussure->getCommentaires() as $key => $value)
            {
                if(!empty($value->getEvaluation()))
                {
                    $note = $value->getEvaluation();
                    // dump($eval);
                    $dataNote[] = $note;
                    // dump($evaluation);
                    $total += $note;
                    
                    if(!empty($dataNote))
                    {
                        $resultat = $total / count($dataNote);
                        $moyenne = round($resultat,1);
                    }

    // #[Route('/details_articles/{id}', name: 'details_articles')]
    // public function index(ChaussureRepository $repoChaussure, Chaussure $chaussure): Response
    // {
        

    //     $chaussure1 = $repoChaussure->findAll(); // offset

    //     $shoes = $chaussure->getId();

        
                }
            }
        // dump(count($dataNote));
        // dump($moyenne);
        // dump($chaussure->getCommentaires());
        

        $id = $this->getUser();
        // $idshoes = $chaussure;
        $formComment = $this->createForm(CommentFormType::class, $commentaire);
        $formComment->handleRequest($request);

        $datacom = $repoCommentaire->findBy(
            array(), 
            array (), 
            10, 
            0);

        $total = 0;

        if($formComment->isSubmitted())
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
            'datachaussure'=>$dataChaussure,
            'tailleChaussure'=>$tailleChaussure,
            'couleurChaussure'=>$couleurChaussure,
            'datacom'=> $datacom,
            'moyenne'=> $moyenne,
            'sizeGroup'=>$sizeGroup
        ]);

    }

    //     return $this->render('details_articles/details_articles.html.twig', [
    //         'chaussure'=> $chaussure1,
    //         'id' => $shoes
    //     ]);
    // }

}
