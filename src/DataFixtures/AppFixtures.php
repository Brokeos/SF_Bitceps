<?php

namespace App\DataFixtures;

use App\Entity\Trainer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $trainer1 = new Trainer();
        $trainer1->setName("Jonathan Smithtreretre")
            ->setCategory("Entraineur Homme")
            ->setPicture("1.jpg")
            ->setColor("#a589a9");

        $manager->persist($trainer1);
        $manager->flush();
    }
}
