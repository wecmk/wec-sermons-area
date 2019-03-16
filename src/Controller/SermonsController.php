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
class SermonsController extends AbstractController {

    private $itemsPerPage = 16;

    /**
     * @Route("/", name="home")     
     */
    public function indexAction(Request $request, \App\Services\Sermons\SermonsSearchService $search) {
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
     * @Route("/series/{value}", name="list_by_series")
     */
    public function searchFieldAction(Request $request, \App\Services\Sermons\SermonsSearchService $search, $value) {
        return $this->render('sermons/index.html.twig', [
                    'results' => $search->searchBySeries($value),
                    'searchQuery' => "",
        ]);
    }

    /**
     * @Route("/speaker/{value}", name="list_by_speaker")
     */
    public function searchSpeakerAction(Request $request, \App\Services\Sermons\SermonsSearchService $search, $value) {
        return $this->render('sermons/index.html.twig', [
                    'results' => $search->searchBySpeaker($value),
                    'searchQuery' => "",
        ]);
    }

    /**
     * @Route("/listofseries", name="list_series")
     */
    public function ListOfSeriesAction(Request $request) {
        // make a database call or other logic
        // to get the "$max" most recent articles
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository(\App\Entity\BibleBooks::class)->findAll();
        $bookNames = array();
        foreach ($books as $book) {
            if (strpos($book->getBook(), '/') === false) {
                $bookNames[] = $book->getBook();
            }
        }
        $allSeries = $em->getRepository(\App\Entity\Series::class)->findAll();
        $seriesNames = array();
        foreach ($allSeries as $series) {
            if (strpos($series->getName(), '/') === false) {
                $seriesNames[] = $series->getName();
            }
        }
        $seriesList = array_diff($seriesNames, $bookNames);

        $bookSeries = array_intersect($bookNames, $seriesNames);

        usort($seriesList, 'strcmp');
        return $this->render('sermons/list_of_series.html.twig', [
                    'books' => $bookSeries,
                    'seriesList' => $seriesList,
                    'searchQuery' => "",
        ]);
    }

    public function convert_smart_quotes($string) {
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
