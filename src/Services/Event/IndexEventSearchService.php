<?php

namespace App\Services\Event;

use Psr\Log\LoggerInterface;
use App\Services\Cache\CacheService;
use Http\Client\HttpClient;
use FOS\ElasticaBundle\Elastica\Index;
use \Elastica\Type;
use Elastica\Query\QueryString;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use App\Entity\Event;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class IndexEventSearchService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $index \FOS\ElasticaBundle\Finder\TransformedFinder */
    private $index;

    /** @var \Doctrine\ORM\EntityManagerInterface $em */
    private $em;
    
    /** @var \App\Repository\EventRepository $repository */
    private $repository;
    
    public function __construct(LoggerInterface $logger, TransformedFinder $index, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->index = $index;
        $this->em = $em;
        $this->repository = $em->getRepository(Event::class);
    }

    public function search($searchTerm)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $fieldQuery = new \Elastica\Query\QueryString();
        $fieldQuery->setQuery($searchTerm);
        $boolQuery->addShould($fieldQuery);

        $searchQuery = new \Elastica\Query($boolQuery);
        $searchQuery->addSort(array('Date' => 'desc', 'Apm' => 'desc'));
        $results = $this->index->find($searchQuery);

        return $results;
    }

    public function searchBySeries($name)
    {
        $query_part = new \Elastica\Query\BoolQuery();
        $nested = new \Elastica\Query\Nested();
        $nested->setPath("Series");

        $nested_bool = new \Elastica\Query\BoolQuery();
        $nested_bool->addMust(
            new \Elastica\Query\Match("Series.Name", $name)
        );
        $nested->setQuery($nested_bool);
        $query_part->addMust($nested);
        
        $searchQuery = new \Elastica\Query($query_part);
        $searchQuery->addSort(array('Date' => 'asc', 'Apm' => 'asc'));
        $results = $this->index->find($searchQuery);
        return $this->index->find($searchQuery);
    }

    public function searchBySpeaker($name)
    {
        $query_part = new \Elastica\Query\BoolQuery();
        $nested = new \Elastica\Query\Nested();
        $nested->setPath("Speaker");

        $nested_bool = new \Elastica\Query\BoolQuery();
        $nested_bool->addMust(
            new \Elastica\Query\Match("Speaker.Name", $name)
        );
        $nested->setQuery($nested_bool);
        $query_part->addMust($nested);

        return $this->index->find($query_part);
    }
}
