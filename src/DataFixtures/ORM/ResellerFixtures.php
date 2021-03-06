<?php

namespace App\DataFixtures\ORM;

use App\Entity\Reseller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\ORM\AdminFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResellerFixtures extends Fixture implements DependentFixtureInterface
{

    private \Faker\Generator $faker;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker =  Factory::create('fr_FR');
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $genre = ["male","female"];


        $reseller0 = new Reseller();
        $reseller0->setName("SFR")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller0->getName() . ".com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller0, "resellSfr"))
            ->setAdmin($this->getReference('admin'))
        ;
        $manager->persist($reseller0);
        $manager->flush();

        $reseller1 = new Reseller();
        $reseller1->setName("Bouygue")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller1->getName() . ".com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller1, "resellBouygue"))
            ->setAdmin($this->getReference('admin'))
        ;
        $manager->persist($reseller1);
        $manager->flush();

        $reseller2 = new Reseller();
        $reseller2->setName("Orange")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller2->getName() . ".com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller2, "resellOrange"))
            ->setAdmin($this->getReference('admin'))
        ;
        $manager->persist($reseller2);
        $manager->flush();

        $reseller3 = new Reseller();
        $reseller3->setName("Free")
            ->setEmail(strtolower($this->faker->firstName($genre[mt_rand(0, 1)]) . "@" . $reseller3->getName() . ".com"))
            ->setPassword($this->passwordEncoder->encodePassword($reseller3, "resellFree"))
            ->setAdmin($this->getReference('admin'))
        ;
        $manager->persist($reseller3);
        $manager->flush();

        //Reference
        $this->addReference('reseller0', $reseller0);
        $this->addReference('reseller1', $reseller1);
        $this->addReference('reseller2', $reseller2);
        $this->addReference('reseller3', $reseller3);
    }


    public function getDependencies(): array
    {
        return array(
            AdminFixtures::class
        );
    }
}
