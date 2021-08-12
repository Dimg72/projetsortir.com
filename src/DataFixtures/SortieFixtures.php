<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1; $i <= 55; $i++)
        {
            $sortie = new Sortie();
            $sortie->setNom($faker->unique()->company());
            $sortie->setCampus($this->getReference("Campus".mt_rand(1,3)));
            $sortie->setDateHeureDebut($faker->dateTimeBetween('11 months', 'now'));
            $sortie->setDateLimiteInscription($faker->dateTimeBetween($sortie->getDateHeureDebut(), 'now'));
            $sortie->setDuree($faker->numberBetween(1,48));
            $sortie->setEtat($this->getReference("Etat".mt_rand(1,6)));
            $sortie->setInfosSortie($faker->sentence(mt_rand(3,8)));
            $sortie->setLieu($this->getReference("Lieu".mt_rand(1,80)));
            $sortie->setNbInscriptionsMax($faker->numberBetween(3,22));
            $sortie->setOrganisateur($this->getReference("Participant".mt_rand(1,20)));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          CampusFixtures::class, EtatFixtures::class, LieuFixtures::class, ParticipantFixtures::class
        ];
    }
}
