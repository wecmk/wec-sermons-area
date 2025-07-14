<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AttachmentMetadataTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new \App\Entity\AttachmentMetadataType("sermon-recording", "Sermon", "The main recording of the sermon. May include the reading."));
        $manager->persist(new \App\Entity\AttachmentMetadataType("childrens-talk-recording", "Childrens Talk", "The Childrens Talk."));
        $manager->persist(new \App\Entity\AttachmentMetadataType("service-sheet", "Service Sheet", "The service sheet."));
        $manager->persist(new \App\Entity\AttachmentMetadataType("speech2text", "Speech", "What is said during the recording, in textual format. This is used to provide a full text search. This may include sermon-notes so this data must never be made public.", false));
        $manager->persist(new \App\Entity\AttachmentMetadataType("sermon-notes", "Sermon Notes", "The original notes provided by the speaker. NEVER TO BE MAKE PUBLIC!", false));
        $manager->persist(new \App\Entity\AttachmentMetadataType("homegroup-sheet", "Home Group Sheet", "Homegroup worksheet"));

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test'];
    }
}
