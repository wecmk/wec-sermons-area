<?php

namespace App\Repository;

use App\Entity\AttachmentMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AttachmentMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentMetadata[]    findAll()
 * @method AttachmentMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachmentMetadata::class);
    }
}
