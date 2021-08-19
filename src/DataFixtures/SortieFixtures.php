<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1; $i <= 70; $i++)
        {
            $sortie = new Sortie();
            $sortie->setNom($faker->unique()->company());
            $sortie->setCampus($this->getReference(Campus::class.mt_rand(1,3)));
            $sortie->setDateLimiteInscription($faker->dateTimeBetween('-3 months', '+8 months'));
            $sortie->setDateHeureDebut($faker->dateTimeBetween($sortie->getDateLimiteInscription(), '+8 months'));
            $sortie->setDuree($faker->numberBetween(15,120));

            $idEtat = mt_rand(1,2);
            $dateDuJour = new \DateTimeImmutable();
            $dateFinActivité = $sortie->getDateHeureDebut()->add(new \DateInterval('PT'.$sortie->getDuree().'M'));

            if ($dateDuJour > $sortie->getDateLimiteInscription()){
                $idEtat = 3;
            }elseif ($dateDuJour > $sortie->getDateHeureDebut() && $dateDuJour < $dateFinActivité){
                $idEtat = 4;
            }elseif ($dateDuJour > $dateFinActivité){
                $idEtat = 5;
            }


            $sortie->setEtat($this->getReference(Etat::class.$idEtat));
            $sortie->setInfosSortie($faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2));
            $sortie->setLieu($this->getReference(Lieu::class.mt_rand(1,80)));
            $sortie->setNbInscriptionsMax($faker->numberBetween(3,22));
            $sortie->setOrganisateur($this->getReference(Participant::class.mt_rand(1,20)));
            $manager->persist($sortie);
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
