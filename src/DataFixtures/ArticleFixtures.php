<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Faker;

// on a creer 3 catégorie pour chaque categorie on lui donne tous ces infomations , et pour chaque  categorie 
// nous creons des articles qui pour chaque article on lui donne des infomations 
// poue chaque article on lui donne un certain nombre de commentaire avec des informations  

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        //on vz creer 3 catégorie fakées
         for($i = 1; $i <= 3; $i++){
            $category=new Category();
            $category->setTitle($faker->word())
                     ->setDescription($faker->paragraph());

                $manager->persist($category);     
              

         }

        //Creer entre 4 et 6 article
       for($j =1; $j <= mt_rand(4,10); $j++){
           $article= new Article();
          
           $content = '<p>'  .join( '</p><p>',$faker->paragraphs(5)). 
           '</p>';
            
            $article->setTitle($faker->word())
                   ->setContent($content)
                   ->setImage($faker->imageUrl())
                   ->setCreatedAt($faker->dateTimeBetween('-6 months'))    
                   ->setCategory($category);

            $manager->persist($article);   

       
                // on donne des commentaires à l'article
                for($k=1; $k <=mt_rand(4,10);$k++){
                    $comment= new Comment();
                    $content = '<p>'  .join( '</p><p>',$faker->paragraphs(2)). 
                    '</p>';

                       
                     $days= (new \DateTime())->diff($article->getCreatedAt())
                     ->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . 
                           $days . 'days'))
                           ->setArticle($article);

                             
                
                           $manager->persist($comment);


       }
    }
       
        $manager->flush();
    }
}
