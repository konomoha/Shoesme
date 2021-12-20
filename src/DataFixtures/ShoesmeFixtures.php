<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Chaussure;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ShoesmeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=1; $i<4; $i++)
        {
            $user = new User;
            $user->setNom($faker->firstName)
                ->setPrenom($faker->lastName)
                ->setEmail($faker->email)
                ->setAdresse("$i rue du test")
                ->setTelephone("013478300$i")
                ->setCodePostal("7898$i")
                ->setVille($faker->country)
                ->setDateNaissance($faker->dateTimeBetween())
                ->setSexe("m")
                ->setPassword("1234");

                $manager->persist($user);
        }
        $manager->flush();

        for($j=1; $j<8; $j++)
        {
            $chaussure = new Chaussure;
            $chaussure->setMarque($faker->word)
                        ->setModel($faker->word)
                        ->setMatiere($faker->word)
                        ->setDescriptif($faker->paragraph())
                        ->setPhoto($faker->word)
                        ->setPrix($faker->numberBetween(10,20))
                        ->setType($faker->word);

                        $manager->persist($chaussure);
        }
        $manager->flush();
        
    }
}
