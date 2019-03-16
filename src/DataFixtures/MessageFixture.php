<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MessageFixture extends Fixture implements \Doctrine\Bundle\FixturesBundle\FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $seriesColossians = new \App\Entity\Series();
        $seriesColossians->setName("Colossians");
        $manager->persist($seriesColossians);
        
        $seriesHebrews = new \App\Entity\Series();
        $seriesHebrews->setName("Hebrews");
        $manager->persist($seriesHebrews);
       
        $speaker = new \App\Entity\Speaker();
        $speaker->setName("Allan Huxtable");
        $manager->persist($speaker);
        
        $sermon = new \App\Entity\Sermon();
        $sermon->setDate(new \DateTime());
        $sermon->setApm("AM");
        $sermon->addSeries($seriesColossians);
        $sermon->setReading("Colossians 1 v. 1 - 23");
        $sermon->setSecondReading("");
        $sermon->setTitle("");
        $sermon->setSpeaker($speaker);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        $manager->persist($sermon);

        $sermon = new \App\Entity\Sermon();
        $sermon->setDate(new \DateTime());
        $sermon->setApm("PM");
        $sermon->addSeries($seriesHebrews);
        $sermon->setReading("Hebrews 2 v.1 - 4");
        $sermon->setSecondReading("");
        $sermon->setTitle("'Must pay more attention'");
        $sermon->setSpeaker($speaker);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        $manager->persist($sermon);

        
        $manager->flush();
    }
    
    public static function getGroups(): array
    {
        return ['dev', 'test'];
    }
}
