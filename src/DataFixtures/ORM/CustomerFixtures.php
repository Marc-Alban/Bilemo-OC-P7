<?php

namespace App\DataFixtures\ORM;

use App\Entity\Customer;
use App\DataFixtures\ORM\ResellerFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
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
        $this->loadCustomer();
    }

    public function loadCustomer()
    {
        $genre = ["male","female"];
        for ($i = 0; $i < 3; $i++) {
            $max = mt_rand(0, 25);
            for ($j = 0; $j < $max; $j++) {
                $customer = new Customer($this->passwordEncoder);
                $customer->setName($this->faker->firstName($genre[mt_rand(0, 1)]))
                    ->setLastName($this->faker->Name())
                    ->setEmail(strtolower($customer->getName()) . "@gmail.com");
                $password = $this->passwordEncoder->encodePassword($customer, 'password');
                $customer->setPassword($password)
                    ->setResellers($this->getReference('reseller' . $i));
                $this->manager->persist($customer);
            }
        }
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            ResellerFixtures::class
        );
    }
}
