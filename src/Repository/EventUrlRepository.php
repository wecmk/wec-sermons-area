<?php

namespace App\Repository;

use App\Entity\EventUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventUrl[]    findAll()
 * @method EventUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventUrl::class);
    }
}
