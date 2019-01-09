<?php

namespace App\Repository;

use App\Entity\QuestionQA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuestionQA|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionQA|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionQA[]    findAll()
 * @method QuestionQA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionQARepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuestionQA::class);
    }

    // /**
    //  * @return QuestionQA[] Returns an array of QuestionQA objects
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
    public function findOneBySomeField($value): ?QuestionQA
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
