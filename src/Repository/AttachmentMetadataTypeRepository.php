<?php

namespace App\Repository;

use App\Entity\AttachmentMetadataType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}
