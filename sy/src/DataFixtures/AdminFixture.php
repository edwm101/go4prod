<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AdminFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setEmail("admin@elfinder.com");
        $admin->role("admin");
        // $manager->persist($product);

        $manager->flush();
    }
}
