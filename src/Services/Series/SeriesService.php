<?php

namespace App\Services\Series;

use Psr\Log\LoggerInterface;
use App\Entity\Series;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class SeriesService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $repository \Doctrine\Common\Persistence\ObjectManager */
    private $repository;
    
    /** @var \Doctrine\ORM\EntityManagerInterface $em */
    private $em;

    public function __construct(LoggerInterface $logger, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(Series::class);
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return array
     */
    public function findBy($name)
    {
        if ($name == "") {
            $name = "Uncategorised";
        }
        return $this->repository->findOneBy(['Name' => $name]);
    }
    
    /**
     * Adds a series
     * @param Series $series
     * @return Series The managed persisted object
     */
    public function add(Series $series)
    {
        $this->em->persist($series);
        $this->em->flush();
        return $series;
    }

    /**
     * Adds a series
     * @param $seriesName
     * @param string $description
     * @param bool $complete
     * @return Series The managed persisted object
     */
    public function create($seriesName, string $description, $complete = false)
    {
        $series = new Series();
        $series->setName($seriesName);
        $series->setDescription($description);
        $series->setComplete($complete);
        return $this->add($series);
    }
}
