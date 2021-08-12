<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
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
            $lieu->setLongitude($faker->unique()->longitude());
            $lieu->setLatitude($faker->unique()->latitute());
            $lieu->setVille($this->getReference("Ville".mt_rand(1,20)));
            $lieu->setRue($faker->unique()->address());

            $this->addReference(Lieu::class.$i);
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
