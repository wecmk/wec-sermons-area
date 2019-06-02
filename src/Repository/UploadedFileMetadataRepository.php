<?php

namespace App\Repository;

use App\Entity\UploadedFileMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedFileMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedFileMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedFileMetadata[]    findAll()
 * @method UploadedFileMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedFileMetadataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedFileMetadata::class);
    }

    // /**
    //  * @return UploadedFileMetadata[] Returns an array of UploadedFileMetadata objects
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
    public function findOneBySomeField($value): ?UploadedFileMetadata
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
