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

    private EventRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(Event::class);
    }

    public function search($searchTerm)
    {
        if ($searchTerm != "*") {
            $searchTerm = "%" . $searchTerm . "%";
        }
        return $this->repository->search($searchTerm);
    }

    public function findAllWithPagination($page, $limit)
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
            ['name' => $name]
        );
        return $this->repository->findBySeries($series);
    }

    public function searchBySeriesUuid($uuid)
    {
        /** @var Series $series */
        $series = $this->em->getRepository(Series::class)->findOneBy(
            ['uuid' => $uuid]
        );
        return $this->repository->findBySeries($series);
    }

    public function searchBySpeaker($name)
    {
        return $this->repository->findBy(["speaker" => $name], ['date' => 'DESC', 'apm' => 'DESC']);
    }
}
