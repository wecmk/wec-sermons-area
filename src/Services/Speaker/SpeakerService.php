<?php

namespace App\Services\Speaker;

use Psr\Log\LoggerInterface;
use App\Entity\Speaker;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class SpeakerService
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
        $this->repository = $em->getRepository(Speaker::class);
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return array
     */
    public function findBy($name)
    {
        return $this->repository->findBy(['Name' => $name]);
    }
    
    /**
     * Adds a Speaker
     * @param Speaker $speaker
     * @return Speaker The managed persisted object
     */
    public function add(Speaker $speaker)
    {
        $this->em->persist($speaker);
        $this->em->flush();
        return $speaker;
    }
    
    /**
     * Adds a series
     * @param Series $series
     * @return Series The managed persisted object
     */
    public function create($name, $organisation = "", $website = "")
    {
        $speaker = new Speaker();
        $speaker->setName($name);
        $speaker->setOrganisation($organisation);
        $speaker->setWebsite($website);
        return $this->add($speaker);
    }
}
