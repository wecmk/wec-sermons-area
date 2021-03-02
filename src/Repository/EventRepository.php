<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     *
     * @param integer $currentPage The current page (passed from controller)
     *
     * @param int $limit
     * @return Paginator
     */
    public function findAllWithPagination($currentPage = 1, $limit = 10)
    {
        // Create our query
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.date', 'DESC')
            ->addOrderBy('s.apm', 'DESC')
            ->getQuery();

        // No need to manually get get the result ($query->getResult())

        return $this->paginate($query, $currentPage, $limit);
    }

    /**
     *
     * @return Paginator
     */
    public function findAllCount()
    {
        /** @var $query Query */
        $query = $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->orderBy('s.date', 'DESC')
            ->addOrderBy('s.apm', 'DESC')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Paginator Helper
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param Query $dql   DQL Query Object
     * @param integer            $page  Current page (defaults to 1)
     * @param integer            $limit The total number per page (defaults to 5)
     *
     * @return Paginator
     */
    public function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    /**
     * Get's last Sunday Services
     *
     * @param $dateString a date string in \DateTime format such as 'last sunday' or 'next sunday'. Time component is ignored
     * @return int|mixed|string
     */
    public function servicesByDate($dateString)
    {
        $date = new \DateTime();
        $date->modify($dateString);

        return $this->createQueryBuilder('event')
            ->leftJoin('event.series', 'series')
            ->Where('event.date = :date')
            ->setParameter('date', $date)
            ->OrderBy('event.date', 'ASC')
            ->addOrderBy('event.apm', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function findAllPublicEvents()
    {
        return $this->createQueryBuilder('event')
            ->leftJoin('event.series', 'series')
            ->where('series.isPublic = :isPublic')
            ->orWhere('event.isPublic = :isPublic')
            ->setParameter('isPublic', true)
            ->orderBy('series.Name', 'ASC')
            ->addOrderBy('event.date', 'DESC')
            ->addOrderBy('event.apm', 'DESC')
            ->getQuery()
            ->execute();
    }

    public function findBySeries(\App\Entity\Series $series)
    {
        return $this->createQueryBuilder('event')
            ->leftJoin('event.series', 'series')
            ->where('series = :series')
            ->setParameter('series', $series)
            ->orderBy('series.Name', 'ASC')
            ->addOrderBy('event.date', 'DESC')
            ->addOrderBy('event.apm', 'DESC')
            ->getQuery()
            ->execute();
    }
}
