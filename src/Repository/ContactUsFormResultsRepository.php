<?php

namespace App\Repository;

use App\Entity\ContactUsFormResults;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactUsFormResults|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactUsFormResults|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactUsFormResults[]    findAll()
 * @method ContactUsFormResults[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactUsFormResultsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactUsFormResults::class);
    }

    // /**
    //  * @return ContactUsFormResults[] Returns an array of ContactUsFormResults objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactUsFormResults
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
