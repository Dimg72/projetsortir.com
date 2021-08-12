<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // $product = new Product();
        for ($i = 1; $i <=20; $i++){
            $participant = new Participant();
            $participant->setNom($faker->lastName());
            $participant->setPrenom($faker->firstName($gender=null));
            $participant->setRoles(["ROLE_USER"]);
            $participant->setActif(true);
            $participant->setAdministrateur(false);
            $participant->setCampus($this->getReference(Campus::class.mt_rand(1,3)));
            $participant->setEmail($faker->unique->email());
            $password = $this->encoder->encodePassword($participant, "password");
            $participant->setPassword($password);
            $participant->setTelephone($faker->unique->phoneNumber());
            $manager->persist($participant);
        }
            $participantAdmin = new Participant();
            $participantAdmin->setNom("admin");
            $participantAdmin->setPrenom("admin");
            $participantAdmin->setRoles(["ROLE_ADMIN"]);
            $participantAdmin->setActif(true);
            $participantAdmin->setAdministrateur(true);
            $participantAdmin->setCampus($this->getReference(Campus::class.mt_rand(1,3)));
            $participantAdmin->setEmail("admin@admin.com");
            $password = $this->encoder->encodePassword($participant, "password");
            $participantAdmin->setPassword($password);
            $participantAdmin->setTelephone($faker->unique->phoneNumber());
            $manager->persist($participantAdmin);

            $manager->flush();
    }

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class
        ];
    }
}
