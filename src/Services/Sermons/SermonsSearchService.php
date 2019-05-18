<?php

namespace App\Services\Sermons;

use Psr\Log\LoggerInterface;
use App\Services\Cache\CacheService;
use Http\Message\MessageFactory;
use Http\Client\HttpClient;
use FOS\ElasticaBundle\Elastica\Index;
use \Elastica\Type;
use Elastica\Query\QueryString;
use FOS\ElasticaBundle\Finder\TransformedFinder;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class SermonsSearchService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $index \FOS\ElasticaBundle\Finder\TransformedFinder */
    private $index;

    public function __construct(LoggerInterface $logger, TransformedFinder $index, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->index = $index;
    }

    public function search($searchTerm)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $fieldQuery = new \Elastica\Query\QueryString();
        $fieldQuery->setQuery($searchTerm);
        $boolQuery->addShould($fieldQuery);
        
        $results = $this->index->find($boolQuery);

        return array(
            'results' => $results,
        );
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

        return array(
            'results' => $this->index->find($query_part),
        );
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

        return array(
            'results' => $this->index->find($query_part),
        );
    }
}
