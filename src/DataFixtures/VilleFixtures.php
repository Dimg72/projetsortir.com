<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // $product = new Product();
        for ($i = 1; $i <=20; $i++){
            $ville = new Ville();
            $ville->setNom($faker->unique()->city());
            $ville->setCodePostal($faker->unique()->postcode());

            $manager->persist($ville);
        }
            $manager->flush();
    }
}
