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
        $id = $chaussure->getId();
        // dd($id);

        //1er filtrage : on sélectionne le modèle de la chaussure grâce à l'id de la chaussure passé dans l'URL
        $model = $chaussure->getModel();

        //A partir du modèle on fait une première recherche de toutes les chaussures correspondantes, on stock le résultat dans dataChaussure et on le transmet au template details_article.html.twig
        $dataChaussure = $chaussureRepo->findBy([
                    'model'=>$model
                    // 'pointure'=>$pointure,
                    // 'couleur'=>$couleur
                    ],
                );
        
        $sizeGroup = $chaussureRepo->findSize($model);//Méthode créée dans le repo de Chaussure permet de trouver toutes les chaussures correspondant à un modèle précis et de les regrouper par tailles (comme ça, on évite les doublons à l'affichage).

        //2eme filtrage : On récupère la valeur de l'indice 'pointure' passé dans l'URL via un formulaire en méthode 'get'
        $pointure=$request->query->get('pointure');

        //On effectue une seconde recherche du même modèle de chaussure mais en appliquant une pointure spécifique. Le résultat est stocké dans la variable 'tailleChaussure' puis transmit au template 'details_article.html.twig'
        $tailleChaussure = $chaussureRepo->findBy([
            'model'=>$model,
            'pointure'=>$pointure
        ]);
        
        //3ème filtrage : On récupère la valeur de l'indice 'couleur' passé dans l'URL via un formulaire en méthode 'get'
        $couleur=$request->query->get('couleur');

        //On effectue une troisième recherche du même modèle de chaussure mais ajoutant à la pointure sélectionnée auparavant une couleur spécifique. Le résultat est stocké dans la variable 'couleurChaussure' puis transmit au template 'details_article'
        $couleurChaussure = $chaussureRepo->findBy([
            'couleur'=>$couleur,
            'pointure'=>$pointure,
            'model'=>$model
        ]);
       

        /////////////////// Méthode d'insertion de commentaires /////////////////////////////////////////////

        //On commence par créer un nouvel objet de l'entité Commentaire et on déclare d'autres variables à zéro ou null qui nous serviront pour les boucles.
        $commentaire = new Commentaire;
        $total= 0;
        $dataNote = [];
        $moyenne=0;
        $resultat=0;
       
        $id = $this->getUser();
        
        //Création du formulaire commentaire qui sera affiché dans le template 'details_article'. $formComment devient alors un objet disposant de plusieurs méthode dont handleRequest qui attend en argument un objet issu de la classe Request et qui permet de transmettre les données de $_POST.
        $formComment = $this->createForm(CommentFormType::class, $commentaire);
        $formComment->handleRequest($request);

        $datacom = $repoCommentaire->findAll();

        //Si le commentaire est soumis, on entre dans le traitement de l'insertion en BDD. La condition isValid() était initialement incluse, mais la nouvelle version de symfony provoque systématiquement des bugs, nous avons donc été contraints de l'enlever pour cet exercice.

        if($formComment->isSubmitted())
        {
            //On fournit aux setteurs les paramètres nécessaires à l'insertion en BDD (dont un objet DateTime)
            $commentaire->setDate(new \DateTime());
            $commentaire->setUser($id);
            $commentaire->setChaussure($chaussure);

            //On se sert de EntityManagerInterface pour conserver les données puis les insérer via la méthode flush()
            $manager->persist($commentaire);
            $manager->flush();

            //Message de validation qui apparaîtra sur le template afin d'informer l'internaute de la bonne réception de son commentaire.
            $this->addFlash('success_comment', "Félicitations! Votre commentaire a bien été posté!");
            //Lorsque le commentaire est validé, on le renvoie sur la page du produit.
            return $this->redirectToRoute('details_article', ['id'=> $chaussure->getId()]);
        }

        //Méthode d'évaluation des produits. On défini d'abord une boucle foreach qui nous permettra d'accéder à toutes les données de chaque commentaires de la table "Commentaire". Ici, l'indice est $key et la valeur est $value.

        foreach($chaussure->getCommentaires() as $key => $value)
        {
            //Une fois entrés dans la boucle, nous ajoutons une condition qui nous permettra de cibler uniquement les commentaires assortis d'une note. En effet, il n'est pas obligatoire d'attribuer une note pour envoyer un commentaire.
            if(!empty($value->getEvaluation()))
            {
                //On stock la note attribuée dans la variable $note définie plus haut.
                $note = $value->getEvaluation();
                
                //$note est ensuite stockée dans le tableau $dataNote également déclaré au préalable.
                $dataNote[] = $note;
                //$on ajoute ensuite la valeur de $note à la variable $total
                $total += $note;
                
                //Si le tableau dataNote n'est pas vide, on stock alors dans la variable $resultat le total des évaluations divisé par le nombre de notes. Puis l'on stock $résultat dans la variable $moyenne
                if(!empty($dataNote))
                {
                    $resultat = $total / count($dataNote);
                    $moyenne = round($resultat,1);
                }

            }
        }

        return $this->render('details_articles/details_articles.html.twig', [
            'formComment' => $formComment->createView(),
            'chaussure'=> $chaussure,
            'datachaussure'=>$dataChaussure,
            'tailleChaussure'=>$tailleChaussure,
            'couleurChaussure'=>$couleurChaussure,
            'datacom'=> $datacom,
            'moyenne'=> $moyenne,
            'sizeGroup'=>$sizeGroup,
            'dataNote'=>$dataNote
        ]);
    }
}
