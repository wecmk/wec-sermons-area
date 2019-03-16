<?php

namespace App\Controller;

use FOS\ElasticaBundle\FOSElasticaBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\ElasticaBundle\Elastica\Client;
use Elastica\Query\QueryString;
use Elastica\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/sermons", name="sermons_")
 */
class SermonsController extends AbstractController
{
    private $itemsPerPage = 16;

    /**
     * @Route("/", name="home")     
     */
    public function indexAction(Request $request, \App\Services\Sermons\SermonsSearchService $search)
    {
        $searchQuery = $request->query->get("searchQuery", "*");
        // Fix search query so that empty matches everything
        $searchQuery = ($searchQuery == "") ? "*" : $searchQuery;
        
        $results = $search->search($searchQuery);
        
        // Fix search display so that a match all (*) is written as an empty string
        $searchQueryDisplay = ($searchQuery == "*") ? "" : $searchQuery;
        
        return $this->render('sermons/index.html.twig', [
                    'results' => $results,
                    'searchQuery' => $searchQueryDisplay,
        ]);
    }

    /**
     * @Route("/series/{value}", name="list_type")
     */
    public function searchFieldAction(Request $request, \App\Services\Sermons\SermonsSearchService $search, $value)
    {
        return $this->render('sermons/index.html.twig', [
            'results' => $search->searchBySeries($value),
    ]);
    }

    /**
     * @Route("/listofseries", name="list_series")
     */
    public function ListOfSeriesAction(Request $request)
    {
        // make a database call or other logic
        // to get the "$max" most recent articles
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository("WecMediaBundle:Biblebooks")->findAll();

        $allSeries = $em->getRepository("WecMediaBundle:Series")->findAll();

        $bookSeries = array_intersect($books, $allSeries);

        $seriesList = array_diff($allSeries, $books);

        $SeriesListFinal = array();
        // Remove sermons in multiple series (seperated with a /)
        foreach ($seriesList as $series) {
            $name = $series->getName();
            if (strpos($name, '/') === false) {
                $SeriesListFinal[] = $name;
            }
            if ($name === '') {
                continue;
            }
        }
        usort($SeriesListFinal, 'strcmp');
        return $this->render('sermons/index.html.twig', [
            'books' => $bookSeries,
            'seriesList' => $SeriesListFinal,
        ]);
    }

    public function convert_smart_quotes($string)
    {
        $search = array(chr(145),
            chr(146),
            chr(147),
            chr(148),
            chr(151));

        $replace = array("'",
            "'",
            '"',
            '"',
            '-');

        return str_replace($search, $replace, $string);
    }
}
