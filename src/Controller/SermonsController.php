<?php

namespace App\Controller;

use App\Entity\BibleBooks;
use App\Entity\Series;
use App\Services\Event\EventSearchService;
use FOS\ElasticaBundle\FOSElasticaBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Request $request
     * @param EventSearchService $search
     * @return Response
     */
    public function indexAction(Request $request, EventSearchService $search)
    {
        $page = $request->query->get('page', 1);
        $limit = 10;

        $searchQuery = $request->query->get("searchQuery", "*");
        // Fix search query so that empty matches everything
        $searchQuery = ($searchQuery == "") ? "*" : $searchQuery;

        $results = $search->search($searchQuery, $page, $limit);

        // Fix search display so that a match all (*) is written as an empty string
        $searchQueryDisplay = ($searchQuery == "*") ? "" : $searchQuery;

        $totalPostsReturned = $results->getIterator()->count();
        $totalPosts = $results->count();
        $iterator = $results->getIterator();
        $maxPages = ceil($results->count() / $limit);
        $thisPage = $page;

        return $this->render('sermons/index.html.twig', [
                    'results' => $results,
                    'enablePagination' => true,
                    'searchQuery' => $searchQueryDisplay,
                    'maxPages' => $maxPages,
                    'thisPage' => $thisPage,
        ]);
    }

    /**
     * @Route("/series/{value}", name="list_by_series")
     */
    public function searchFieldAction(Request $request, EventSearchService $search, $value)
    {
        return $this->render('sermons/index.html.twig', [
                    'results' => $search->searchBySeries($value),
                    'searchQuery' => "",
        ]);
    }

    /**
     * @Route("/speaker/{value}", name="list_by_speaker")
     */
    public function searchSpeakerAction(Request $request, EventSearchService $search, $value)
    {
        return $this->render('sermons/index.html.twig', [
                    'results' => $search->searchBySpeaker($value),
                    'searchQuery' => "",
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
        $books = $em->getRepository(BibleBooks::class)->findAll();
        $bookNames = array();
        foreach ($books as $book) {
            if (strpos($book->getBook(), '/') === false) {
                $bookNames[] = $book->getBook();
            }
        }
        $allSeries = $em->getRepository(Series::class)->findAll();
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
