<?php

namespace App\Repository;

use App\Entity\QuestionSeries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuestionSeries|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionSeries|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionSeries[]    findAll()
 * @method QuestionSeries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionSeriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionSeries::class);
    }

    // /**
    //  * @return QuestionSeries[] Returns an array of QuestionSeries objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionSeries
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
