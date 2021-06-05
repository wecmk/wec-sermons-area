<?php

namespace App\Repository;

use App\Entity\Series;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Series|null find($id, $lockMode = null, $lockVersion = null)
 * @method Series|null findOneBy(array $criteria, array $orderBy = null)
 * @method Series[]    findAll()
 * @method Series[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Series::class);
    }

    public function findByIsPublic($isPublic)
    {
        return $this->findBy(["isPublic" => $isPublic], ['Date']);
    }

    public function findAllByQuery($query)
    {
        $query = $this->createQueryBuilder("r")
            ->where('r.id = ?1')
            ->orWhere('r.uuid LIKE ?2')
            ->orWhere('r.name LIKE ?3')
            ->orWhere('r.name LIKE ?4')
            ->setParameter(1, $query)
            ->setParameter(2, "%" . $query . "%")
            ->setParameter(3, "%" . $query . "%")
            ->setParameter(4, "%" . $query . "%")
            ->getQuery();

        return $query->execute();
    }
}
