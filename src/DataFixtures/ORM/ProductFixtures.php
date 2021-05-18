<?php

namespace App\DataFixtures\ORM;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('XIAOMI')
            ->setDescription('Achetez le Redmi Note 7 sur le site officiel. La nouvelle expérience du haut-de-gamme. Capturez tout avec 48 MP. 4000mAh(typ). Batterie ultra-haute capacité.')
            ->setCategory((array) 'Redmi Note 7')
            ->setPrice(165.85)
                ->setPropertys([64,'Black'])
        ;
        $manager->persist($product);
        $manager->flush();

        $product1 = new Product();
        $product1->setName('Huawei')
            ->setDescription('Le Huawei P20 Lite est le petit frère du Huawei P20 reprenant quelques fonctionnalités disponibles sur son grand-frère à un prix plus abordable.')
            ->setCategory((array) 'P20 Lite')
            ->setPrice(188.34)
                ->setPropertys([64,'White'])
        ;
        $manager->persist($product1);
        $manager->flush();

        $product2 = new Product();
        $product2->setName('Samsung')
            ->setDescription('Si pour vous les Galaxy S10 sont vraiment trop onéreux, alors peut-être que le Galaxy A50 pourrait être le bon choix.')
            ->setCategory((array)'Galaxy A50')
            ->setPrice(275.99)
                ->setPropertys([32,'Red'])
        ;
        $manager->persist($product2);
        $manager->flush();


        $product3 = new Product();
        $product3->setName('Huawei')
            ->setDescription('Le Huawei P Smart 2019 est un smartphone de milieu de gamme annoncé en décembre 2018 et est le successeur de la gamme P8 Lite.')
            ->setCategory((array)'P Smart 2019')
            ->setPrice(164.90)
            ->setPropertys([16,'White'])
        ;
        $manager->persist($product3);
        $manager->flush();

        $product4 = new Product();
        $product4->setName('Apple')
            ->setDescription('Sans se presser, Apple fait évoluer son concept formel d\'iPhone 6 lancé en 2014. Suppression de la prise mini-Jack.')
            ->setCategory((array)'Iphone 7')
            ->setPrice(254.00)
            ->setPropertys([64,'Black'])
        ;
        $manager->persist($product4);
        $manager->flush();


        $product5 = new Product();
        $product5->setName('Honor')
            ->setDescription('Au final, ce Honor 8X offre des performances tout à fait honnêtes dans un corps plutôt élégant. ')
            ->setCategory((array)'8X')
            ->setPrice(199.00)
            ->setPropertys([16,'White'])
        ;
        $manager->persist($product5);
        $manager->flush();


        $product6 = new Product();
        $product6->setName('Nokia')
            ->setDescription('Avec ses formes courbées et ergonomiques ainsi que son clavier Island qui facilite la saisie et la composition.')
            ->setCategory((array)'105')
            ->setPrice(1990.90)
            ->setPropertys([128,'Black'])
        ;
        $manager->persist($product6);
        $manager->flush();
    }
}
