<?php

namespace App\Repository;

use App\Entity\Apm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Apm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apm[]    findAll()
 * @method Apm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApmRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Apm::class);
    }

    // /**
    //  * @return Apm[] Returns an array of Apm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Apm
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
