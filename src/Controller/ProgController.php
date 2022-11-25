<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\FileUploader;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgController extends AbstractController
{   
    // n'importe quel commntaire qui commence avec un @ est une annotation , l'annotation au dessous s'applle route 
   /** 
     * @Route("/prog", name ="prog")
    */
    public function index(ArticleRepository $articleRepo): Response
    {

        // on va recuperer la liste des articles et lesndonnées a twig 
        //pour les afficher pour cela nous avons besoin d'un repository 
        // qui va permettre de selectionner les donnes dans une table
        // on declare une variable repo et on va dire au controller, que je veux discuter avec doctrine , qui va me donner le repository que je le chercher par rapport a la classe article 
        

        // on declare une variable article qui est egale =articleRepo dont se trouve la methode find all 

        $articles= $articleRepo->findAll();

        return $this->render('prog/index.html.twig', [
            'controller_name' => 'ProgController',
            'articles'=> $articles
        ]);
    }

    /**
     * @Route("/", name ="home")
     */

    //je veux que cette fonction quand j'applle mo site .com/rien c'est à dire la page d'acceul du site 
    public function home() {
        // return $this->render qui va me permettre d'appler un fichier twig pour pouvoir l'afficher danslz fonction render n
        // mettons l'addresse du fichier twig 
        return $this->render('prog/home.html.twig');

     }
      /**
 * @Route("/prog/new", name="prog_create")
* @Route("/prog/{id}/edit", name="prog_edit")
 */
     public function create(Article $article =null,  Request $request , EntityManagerInterface $entityManager,){
        //instance de l'objet article avec une condition
 if(!$article){
   $article = new Article();
 }
    //creation d'un formulaire avec createfomrbuilder
    // les champs qui m'interesse 
    //reliser les champs à entité
 
   
                 // ->add('title')
                 
 
                  // ->add('content')
 
 
                  // ->add('image')
 
 
                 // ->getForm();
 
    // // on demande au fomrulaire d'analyser la requet http que je te la passe ici 
     $form = $this->createForm(ArticleType::class,$article);
 
      $form->handleRequest($request);
    //   //methode de la classe form qui va voir  si le fomulaire a été soumis ou enregistré ou le form a été valide
       if($form->isSubmitted() && $form->isValid())
       
       
       {
       
       


         if(!$article->getId()){
            $article->setCreatedAt(new \DateTime());
 
          }
        //persisiter l'article 
       $entityManager->persist($article);
        //balacner la requete 
       $entityManager->flush();
 
       return $this->redirectToRoute('prog_show',['id' => $article->getId()]);
 
      }
 
 
   return $this->render('prog/create.html.twig',[
      'formArticle' => $form->createView(),
      'editMode'=>$article->getId() !== null
   ]);
}


   /**
  * @Route("/prog/{id}", name="prog_show")
  */
  public function show(Article $article, Request $request,EntityManagerInterface $entityManager )

  {  
       $comment=new Comment();
       $form= $this->createForm(CommentType::class,$comment);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
            
        $comment->setCreatedAt(new \DateTime())
                 ->setArticle($article)
                 ->setAuthor($this->getUser()->getNom()." ".$this->getUser()->getPrenom());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('prog_show',['id' => $article->getId()]);

       }
  
    return $this->render('prog/show.html.twig',[
      'article' => $article,
      'commentForm'=>$form->createView()
    ]);
  }


}
