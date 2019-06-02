<?php

namespace App\Services\Sermons;

use Psr\Log\LoggerInterface;
use App\Entity\Sermon;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */
class SermonsService
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
        $this->repository = $em->getRepository(Sermon::class);
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
        return $this->repository->findBy(['Name' => $name]);
    }
    
    /**
     * Adds a sermon
     * @param Sermon $sermon
     * @return Sermon The managed persisted object
     */
    public function add(Sermon $sermon)
    {
        $this->em->persist($sermon);
        $this->em->flush();
        return $sermon;
    }
}
