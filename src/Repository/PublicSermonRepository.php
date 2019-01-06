<?php

namespace App\Repository;

use App\Entity\PublicSermon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PublicSermon|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicSermon|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicSermon[]    findAll()
 * @method PublicSermon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicSermonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PublicSermon::class);
    }

    // /**
    //  * @return PublicSermon[] Returns an array of PublicSermon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PublicSermon
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
