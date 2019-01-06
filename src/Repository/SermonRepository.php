<?php

namespace App\Repository;

use App\Entity\Sermon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sermon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sermon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sermon[]    findAll()
 * @method Sermon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SermonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sermon::class);
    }

    // /**
    //  * @return Sermon[] Returns an array of Sermon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sermon
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
