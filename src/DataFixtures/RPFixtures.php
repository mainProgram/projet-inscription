<?php

namespace App\DataFixtures;

use App\Entity\RP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RPFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rp = new RP();
        $rp->setNom("Diop");
        $rp->setPrenom("Fama");
        $rp->setEmail("fazeyna@gmail.com");
        $rp->setPassword("passer");
        $manager->persist($rp);
        $manager->flush();
    }
}
