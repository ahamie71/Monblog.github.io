<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;

class SearchController extends AbstractController
{
    #[Route('/prog/search', name: 'app_search')]
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            
        ]);
    }
    #[Route('/search', name: 'prog_search')]


       public function search(Request $request, ArticleRepository $articleRepo  ): Response {
        
        $articles= new Article();

       $form = $this->createFormBuilder()


     
       ->add('query', TextType::class, [
        'label' => false,
        'attr' => [ 
            'class' => 'form-control',
            'placeholder' => 'Entrez un mot-clé'
        ]
    ])
    ->add('recherche', SubmitType::class, [
        'attr' => [
            'class' => 'btn btn-primary'
        ]
    ])
    ->getForm();


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){

    
        
        

       
       
       $articles=$articleRepo->findAllArticleBysearch($form->getData()['query']);

    
       
    }

       
    
    
       return $this->render('search/index.html.twig', [
        'form' => $form->createView(),
        'articles' => $articles,
        
    ]);

}


}