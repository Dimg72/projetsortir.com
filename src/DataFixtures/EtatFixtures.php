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
        $etat->setLibelle("Créée");
        $manager->persist($etat);
        $this->addReference(Etat::class.'1',$etat);

        $etat1 = new Etat();
        $etat1->setLibelle("Ouverte");
        $manager->persist($etat1);
        $this->addReference(Etat::class.'2',$etat1);

        $etat2 = new Etat();
        $etat2->setLibelle("Cloturée");
        $manager->persist($etat2);
        $this->addReference(Etat::class.'3',$etat2);

        $etat3 = new Etat();
        $etat3->setLibelle("Activité en cours");
        $manager->persist($etat3);
        $this->addReference(Etat::class.'4',$etat3);

        $etat4 = new Etat();
        $etat4->setLibelle("Passée");
        $manager->persist($etat4);
        $this->addReference(Etat::class.'5',$etat4);

        $etat5 = new Etat();
        $etat5->setLibelle("Annulée");
        $manager->persist($etat5);
        $this->addReference(Etat::class.'6',$etat5);




        $manager->flush();
    }
}
