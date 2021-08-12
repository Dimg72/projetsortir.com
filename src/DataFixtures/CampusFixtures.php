<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();

            $campus = new Campus();
            $campus->setNom("Nantes");
            $manager->persist($campus);

            $this->addReference(Campus::class.'1',$campus);

            $campus1 = new Campus();
            $campus1->setNom("Brest");
            $manager->persist($campus1);

            $this->addReference(Campus::class.'2',$campus1);

            $campus2 = new Campus();
            $campus2->setNom("Niort");
            $manager->persist($campus2);

            $this->addReference(Campus::class.'3',$campus2);

            $manager->flush();

    }
}
