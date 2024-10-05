<?php

namespace App\Services\Event;

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Event;

/*
 * @author Samuel Pearce <samuelcpearce@gmail.com>
 */
class EventService
{
    private readonly EventRepository $repository;

    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(Event::class);
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
     * @param Event $sermon
     * @return Event The managed persisted object
     */
    public function add(Event $sermon): Event
    {
        $this->em->persist($sermon);
        $this->em->flush();
        return $sermon;
    }

    /**
     * Find Event by ID
     * @param type $id
     * @return Event
     */
    public function getById($id): Event
    {
        return $this->repository->find($id);
    }

    public function deleteById($id)
    {
        $event = $this->em->getReference(Event::class, $id);
        $this->em->remove($event);
        $this->em->flush();
    }
}
