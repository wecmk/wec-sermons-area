<?php

namespace App\Services\Event;

use Psr\Log\LoggerInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use App\Entity\Event;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class DbEventSearchService implements EventSearchService
{
    /* @var $logger LoggerInterface */
    private $logger;

    /* @var $index \FOS\ElasticaBundle\Finder\TransformedFinder */
    private $index;

    /** @var \Doctrine\ORM\EntityManagerInterface $em */
    private $em;
    
    /** @var \App\Repository\EventRepository $repository */
    private $repository;
    
    public function __construct(LoggerInterface $logger, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(Event::class);
    }

    public function search($searchTerm)
    {
        return $this->repository->findBy([], [
            "Date" => "DESC",
            "Apm" => "DESC"
        ]);
    }

    public function searchBySeries($name)
    {
        /** @var \App\Entity\Series $series */
        $series = $this->em->getRepository(\App\Entity\Series::class)->findOneBy(
            ['Name' => $name]
        );
        return $series->getEvents();
    }

    public function searchBySpeaker($name)
    {
        /** @var \App\Entity\Speaker $speaker */
        $speaker = $this->em->getRepository(\App\Entity\Speaker::class)->findOneBy(
            ['Name' => $name]
        );
        return $speaker->getEvent();
    }
}
