<?php

namespace App\Repository;

use App\Entity\BibleBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BibleBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method BibleBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method BibleBooks[]    findAll()
 * @method BibleBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BibleBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BibleBooks::class);
    }
}
