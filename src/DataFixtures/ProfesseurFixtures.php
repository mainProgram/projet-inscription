<?php

namespace App\DataFixtures;

use App\Entity\Professeur;
use App\Entity\RP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfesseurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {    
        $rp = new RP();
        $rp->setNom("Diop");
        $rp->setPrenom("Fama");
        $rp->setEmail("fazeyuuuua@gmail.com");
        $rp->setPassword("passer");
        $manager->persist($rp);

        $professeur = new Professeur();
        $professeur->setNom("Diop");
        $professeur->setPrenom("Awa");
        $professeur->setGrade("Doctorat");
        $professeur->setRP($rp);

        $manager->persist($professeur);
        $manager->flush();
    }
}
