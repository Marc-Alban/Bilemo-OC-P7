<?php

namespace App\DataFixtures\ORM;

use App\Entity\Customer;
use App\DataFixtures\ORM\ResellerFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    private \Faker\Generator $faker;
    private ObjectManager $manager;

    public function __construct()
    {
        $this->faker =  Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadCustomer();
    }

    public function loadCustomer(): void
    {
        $genre = ["male","female"];
        for ($i = 0; $i < 3; $i++) {
            $max = mt_rand(0, 25);
            for ($j = 0; $j < $max; $j++) {
                $customer = new Customer();
                $customer->setName($this->faker->firstName($genre[mt_rand(0, 1)]))
                    ->setLastName($this->faker->lastName())
                    ->setEmail(strtolower($customer->getName()) . "@gmail.com")
                    ->setCustomersResellers($this->getReference('reseller' . $i));
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
