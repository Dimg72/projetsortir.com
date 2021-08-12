<?php

namespace App\DataFixtures;

use App\Entity\Etat;
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
        $this->addReference(Etat::class.'1',$etat);

        $etat1 = new Etat();
        $etat1->setLibelle("ouverte");
        $manager->persist($etat1);
        $this->addReference(Etat::class.'2',$etat);

        $etat2 = new Etat();
        $etat2->setLibelle("cloturée");
        $manager->persist($etat2);
        $this->addReference(Etat::class.'3',$etat);

        $etat3 = new Etat();
        $etat3->setLibelle("activité en cours");
        $manager->persist($etat3);
        $this->addReference(Etat::class.'4',$etat);

        $etat4 = new Etat();
        $etat4->setLibelle("passée");
        $manager->persist($etat4);
        $this->addReference(Etat::class.'5',$etat);

        $etat5 = new Etat();
        $etat5->setLibelle("annulée");
        $manager->persist($etat5);
        $this->addReference(Etat::class.'6',$etat);




        $manager->flush();
    }
}
