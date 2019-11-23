<?php

namespace App\Repository;

use App\Entity\BibleBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    // /**
    //  * @return BibleBooks[] Returns an array of BibleBooks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BibleBooks
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
