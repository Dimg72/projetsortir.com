<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // $product = new Product();

        $etat = new Etat();
        $etat->setLibelle("crée");
        $manager->persist($etat);

        $etat1 = new Etat();
        $etat1->setLibelle("ouverte");
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle("cloturée");
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle("activité en cours");
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle("passée");
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle("annulée");
        $manager->persist($etat5);




        $manager->flush();
    }
}
