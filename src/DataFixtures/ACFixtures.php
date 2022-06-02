<?php

namespace App\DataFixtures;

use App\Entity\AC;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ACFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ac = new AC();
        $ac->setNom("Diop");
        $ac->setPrenom("Awa");
        $ac->setEmail("awadiop456@gmail.com");
        $ac->setPassword("passer");
        $manager->persist($ac);
        $manager->flush();
    }
}
