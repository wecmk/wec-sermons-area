<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SeriesFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(new \App\Entity\Series(null, "Visiting Speakers"));

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test'];
    }
}
