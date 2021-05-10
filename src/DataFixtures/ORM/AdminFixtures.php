<?php

namespace App\DataFixtures\ORM;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private \Faker\Generator $faker;
    private ObjectManager $manager;
    private UserPasswordEncoderInterface $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker =  Factory::create('fr_FR');
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadAdmin();
    }

    public function loadAdmin()
    {
        $genre = ["male","female"];
        $admin = new Admin($this->passwordEncoder);
        $admin->setName($this->faker->firstName($genre[mt_rand(0, 1)]))
              ->setEmail("admin@bilemo.com");
        $password = $this->passwordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $this->manager->persist($admin);
        $this->manager->flush();

        $this->addReference('admin', $admin);
 
    }
}
