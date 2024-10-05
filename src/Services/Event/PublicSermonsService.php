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
    /* @var $repository ObjectManager */
    private $repository;

    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(Event::class);
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
