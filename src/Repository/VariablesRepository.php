<?php

namespace App\Repository;

use App\Entity\Variables;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Variables|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variables|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variables[]    findAll()
 * @method Variables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Variables::class);
    }

    public function findByName($name): ?Variables
    {
        $value = $this->createQueryBuilder('v')
            ->andWhere('v.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return $value ?? new Variables($name);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function setByName($name, $value, $persistImmediately = true): void
    {
        $variable = $this->findByName($name);
        if ($value == null || $variable->getValue() == null) {
            $variable = new Variables();
            $variable->setName($name);
        }
        $variable->setValue($value);

        if ($persistImmediately) {
            $this->getEntityManager()->persist($variable);
            $this->flush();
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
    }
}
