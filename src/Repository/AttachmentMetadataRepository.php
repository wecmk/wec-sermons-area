<?php

namespace App\Repository;

use App\Entity\AttachmentMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AttachmentMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentMetadata[]    findAll()
 * @method AttachmentMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentMetadataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AttachmentMetadata::class);
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
