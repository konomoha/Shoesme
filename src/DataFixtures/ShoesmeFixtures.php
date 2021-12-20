<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Chaussure;
use App\Entity\Couleur;
use App\Entity\Taille;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ShoesmeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // for($i=1; $i<4; $i++)
        // {
        //     $user = new User;
        //     $user->setNom($faker->firstName)
        //         ->setPrenom($faker->lastName)
        //         ->setEmail($faker->email)
        //         ->setAdresse("$i rue du test")
        //         ->setTelephone("013478300$i")
        //         ->setCodePostal("7898$i")
        //         ->setVille($faker->country)
        //         ->setDateNaissance($faker->dateTimeBetween())
        //         ->setSexe("m")
        //         ->setPassword("1234");

        //         $manager->persist($user);
        // }
        // $manager->flush();

        // for($j=1; $j<6; $j++)
        // {
        //    $couleur = new Couleur;
        //    $couleur->setNomCouleur(array_rand(['Blanc', 'Bleu', 'Rouge', 'Noir', 'Gris', 'Beige' ]))
        //             ->addTaille->findAll()



        
        // $manager->flush();
        
    }
}
