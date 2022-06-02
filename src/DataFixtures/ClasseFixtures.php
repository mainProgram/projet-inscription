<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class ClasseFixtures extends Fixture
{
    private Generator $faker;
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 3 ; $i++) { 
           $classe = new Classe();
           $classe->setLibelle($this->faker->word(7));
           $classe->setNiveau($this->faker->word(15));
           $classe->setFiliere($this->faker->word(10));
           $manager->persist($classe);
        }
        $manager->flush();
    }

    public function __construct(){
        $this->faker = Factory::create("fr_Fr");
    }
}
