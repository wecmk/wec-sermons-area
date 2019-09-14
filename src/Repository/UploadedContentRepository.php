<?php

namespace App\Repository;

use App\Entity\UploadedContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedContent[]    findAll()
 * @method UploadedContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedContentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedContent::class);
    }

    // /**
    //  * @return UploadedContent[] Returns an array of UploadedContent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UploadedContent
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
