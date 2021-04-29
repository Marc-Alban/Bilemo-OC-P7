<?php

namespace App\DataFixtures\ORM;

use App\Entity\Reseller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResellerFixtures extends Fixture
{

    private \Faker\Generator $faker;
    private UserPasswordEncoderInterface $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker =  Factory::create('fr_FR');
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $genre = ["male","female"];


        $reseller0 = new Reseller($this->passwordEncoder);
        $reseller0->setName("SFR")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller0->getName() . "com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller0, "resellSfr"))
        ;
        $manager->persist($reseller0);
        $manager->flush();

        $reseller1 = new Reseller($this->passwordEncoder);
        $reseller1->setName("Bouygue")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller1->getName() . "com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller1, "resellBouygue"))
        ;
        $manager->persist($reseller1);
        $manager->flush();

        $reseller2 = new Reseller($this->passwordEncoder);
        $reseller2->setName("Orange")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller2->getName() . "com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller2, "resellOrange"))
        ;
        $manager->persist($reseller2);
        $manager->flush();

        $reseller3 = new Reseller($this->passwordEncoder);
        $reseller3->setName("Free")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller3->getName() . "com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller3, "resellFree"))
        ;
        $manager->persist($reseller3);
        $manager->flush();

        //Reference
        $this->addReference('reseller0', $reseller0);
        $this->addReference('reseller1', $reseller1);
        $this->addReference('reseller2', $reseller2);
        $this->addReference('reseller3', $reseller3);
    }
}