<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1; $i <= 80; $i++)
        {
            $lieu = new Lieu();
            $lieu->setNom($faker->unique()->company());
            $lieu->setLongitude($faker->longitude($min = -180, $max = 180));
            $lieu->setLatitude($faker->latitude($min = -90, $max = 90));
            $lieu->setVille($this->getReference(Ville::class.mt_rand(1,20)));
            $lieu->setRue($faker->unique()->address());

            $this->addReference(Lieu::class.$i,$lieu);
            $manager->persist($lieu);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            VilleFixtures::class
        ];
    }
}
