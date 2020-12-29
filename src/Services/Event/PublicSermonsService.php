<?php

namespace App\Services\Event;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Event;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */
class PublicSermonsService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $repository ObjectManager */
    private $repository;

    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(Event::class);
    }

    /**
     * Returns all public sermons
     * @return array
     */
    public function all()
    {
        return $this->repository->findBy(['IsPublic' => true]);
    }
}
