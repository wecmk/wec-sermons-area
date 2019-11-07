<?php

namespace App\Repository;

use App\Entity\AttachmentMetadataType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AttachmentMetadataType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentMetadataType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentMetadataType[]    findAll()
 * @method AttachmentMetadataType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentMetadataTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachmentMetadataType::class);
    }

    // /**
    //  * @return AttachmentMetadataType[] Returns an array of AttachmentMetadataType objects
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
    public function findOneBySomeField($value): ?AttachmentMetadataType
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
