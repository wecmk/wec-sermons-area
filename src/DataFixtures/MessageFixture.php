<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\AttachmentMetadataRepository;

class MessageFixture extends Fixture implements \Doctrine\Bundle\FixturesBundle\FixtureGroupInterface
{
    /** @var AttachmentMetadataTypeRepository $attachmentMetadataTypeRepository */
    private $attachmentMetadataTypeRepository;
    
    public function __construct(\App\Repository\AttachmentMetadataTypeRepository $attachmentMetadataTypeRepository)
    {
        $this->attachmentMetadataTypeRepository = $attachmentMetadataTypeRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        $seriesColossians = new \App\Entity\Series();
        $seriesColossians->setName("Colossians");
        $manager->persist($seriesColossians);
        
        $seriesVisitingSpeaker = new \App\Entity\Series();
        $seriesVisitingSpeaker->setName("Visiting Speaker");
        $manager->persist($seriesVisitingSpeaker);
        
        $seriesUncategorised = new \App\Entity\Series();
        $seriesUncategorised->setName("Uncategorised");
        $manager->persist($seriesUncategorised);
        
        $seriesHebrews = new \App\Entity\Series();
        $seriesHebrews->setName("Hebrews");
        $manager->persist($seriesHebrews);
       
        $speakerAllan = new \App\Entity\Speaker();
        $speakerAllan->setName("Allan Huxtable");
        $manager->persist($speakerAllan);
        
        $SpeakerRoger = new \App\Entity\Speaker();
        $SpeakerRoger->setName("Roger March");
        $manager->persist($SpeakerRoger);
        
        $speakerVisitorWithLink = new \App\Entity\Speaker();
        $speakerVisitorWithLink->setName("Visiting Speaker w link");
        $speakerVisitorWithLink->setOrganisation("Organisation");
        $speakerVisitorWithLink->setWebsite("https://www.wecmk.org");
        $manager->persist($speakerVisitorWithLink);
        
        $sermon = new \App\Entity\Event();
        $sermon->setDate(new \DateTime());
        $sermon->setApm("AM");
        $sermon->addSeries($seriesColossians);
        $sermon->setReading("Colossians 1 v. 1 - 23");
        $sermon->setSecondReading("");
        $sermon->setTitle("");
        $sermon->setSpeaker($speakerAllan);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        
        $attachmentMetadata = new \App\Entity\AttachmentMetadata();
        $attachmentMetadata->setMimeType("audio/mpeg");
        $attachmentMetadata->setContentLength("200");
        $attachmentMetadata->setComplete(true);
        $attachmentMetadata->setHash("asdf");
        $attachmentMetadata->setIsPublic(true);
        $attachmentMetadata->setExtension(".mp3");

        $type = $this->attachmentMetadataTypeRepository->findOneBy(["type" => "sermon-recording"]);
        $attachmentMetadata->setType($type);
        $sermon->addAttachmentMetadata($attachmentMetadata);
        $manager->persist($sermon);

        $sermon = new \App\Entity\Event();
        $sermon->setDate(new \DateTime());
        $sermon->setApm("PM");
        $sermon->addSeries($seriesHebrews);
        $sermon->setReading("Hebrews 2:1-4");
        $sermon->setSecondReading("");
        $sermon->setTitle("'Must pay more attention'");
        $sermon->setSpeaker($speakerAllan);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        $manager->persist($sermon);

        $sermon = new \App\Entity\Event();
        $sermon->setDate(new \DateTime());
        $sermon->setApm("AM");
        $sermon->addSeries($seriesUncategorised);
        $sermon->setReading("Psalm 119 v. 89 - 106");
        $sermon->setSecondReading("");
        $sermon->setTitle("A lesson from the reformation");
        $sermon->setSpeaker($SpeakerRoger);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        $manager->persist($sermon);
       
        $sermon = new \App\Entity\Event();
        $sermon->setDate(\DateTime::createFromFormat('Y-m-d', "2016-10-23"));
        $sermon->setApm("THU");
        $sermon->addSeries($seriesVisitingSpeaker);
        $sermon->setReading("Mark 6 v. 1 - 15");
        $sermon->setSecondReading("");
        $sermon->setTitle("Christianity; a heart and spirit religion");
        $sermon->setSpeaker($speakerVisitorWithLink);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        
        $attachmentMetadata = new \App\Entity\AttachmentMetadata();
        $attachmentMetadata->setMimeType("audio/mpeg");
        $attachmentMetadata->setContentLength("250");
        $attachmentMetadata->setComplete(true);
        $attachmentMetadata->setHash("2346afg");
        $attachmentMetadata->setIsPublic(true);
        $attachmentMetadata->setExtension(".mp3");

        $type = $this->attachmentMetadataTypeRepository->findOneBy(["type" => "sermon-recording"]);
        $attachmentMetadata->setType($type);
        $sermon->addAttachmentMetadata($attachmentMetadata);
        $manager->persist($sermon);
        
        $sermon = new \App\Entity\Event();
        $sermon->setDate(\DateTime::createFromFormat('d-m-Y', "26-05-2013"));
        $sermon->setApm("AM");
        
        $seriesBaptisms = new \App\Entity\Series();
        $seriesBaptisms->setName("Baptisms");
        $manager->persist($seriesBaptisms);
        
        $sermon->addSeries($seriesBaptisms);
        $sermon->addSeries($seriesColossians);
        $sermon->setReading("Philippians 3 - 4 v. 1");
        $sermon->setSecondReading("Mark 15 v. 21 - 39");
        $sermon->setTitle("A longish title for a baptism (Rosie and Jim's Baptism)");
        $sermon->setSpeaker($speakerAllan);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        
        $manager->persist($sermon);
        
        $sermon = new \App\Entity\Event();
        $sermon->setDate(\DateTime::createFromFormat('d-m-Y', "16-01-2005"));
        $sermon->setApm("AM");
        $sermon->addSeries($seriesColossians);
        $sermon->setReading("Colossians 2 v. 1 - 23");
        $sermon->setSecondReading("");
        $sermon->setTitle("");
        $sermon->setSpeaker($speakerAllan);
        $sermon->setCorrupt(false);
        $sermon->setIsPublic(true);
        $sermon->setTags("");
        $sermon->setPublicComments("");
        $sermon->setPrivateComments("");
        
        $attachmentMetadata = new \App\Entity\AttachmentMetadata();
        $attachmentMetadata->setMimeType("application/pdf");
        $attachmentMetadata->setContentLength("200");
        $attachmentMetadata->setComplete(true);
        $attachmentMetadata->setHash("asdf");
        $attachmentMetadata->setIsPublic(true);
        $attachmentMetadata->setExtension(".pdf");
        
        $type = $this->attachmentMetadataTypeRepository->findOneBy(["type" => "service-sheet"]);
        $attachmentMetadata->setType($type);
        $sermon->addAttachmentMetadata($attachmentMetadata);
        $manager->persist($sermon);
        
        $manager->flush();
    }
    
    public static function getGroups(): array
    {
        return ['dev', 'test'];
    }
}
