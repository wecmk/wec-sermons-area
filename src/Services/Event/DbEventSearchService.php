<?php

namespace App\Services\Event;

use App\Entity\Series;
use App\Entity\Speaker;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /* @var $index TransformedFinder */
    private $index;

    /** @var EntityManagerInterface $em */
    private $em;
    
    /** @var EventRepository $repository */
    private $repository;
    
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(Event::class);
    }

    public function search($searchTerm, $page, $limit)
    {
        return $this->repository->findAllWithPagination($page, $limit);
    }

    public function searchMaxPagesItems()
    {
        return $this->repository->findAllCount();
    }

    public function searchBySeries($name)
    {
        /** @var Series $series */
        $series = $this->em->getRepository(Series::class)->findOneBy(
            ['Name' => $name]
        );
        return $series->getEvents();
    }

    public function searchBySpeaker($name)
    {
        /** @var Speaker $speaker */
        $speaker = $this->em->getRepository(Speaker::class)->findOneBy(
            ['Name' => $name]
        );
        return $speaker->getEvent();
    }
}
