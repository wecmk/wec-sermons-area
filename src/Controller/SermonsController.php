<?php

namespace App\Controller;

use App\Entity\BibleBooks;
use App\Entity\Event;
use App\Entity\Series;
use App\Services\Event\EventSearchService;
use App\Services\Event\IndexEventSearchService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function indexAction(Request $request, EventSearchService $search, IndexEventSearchService $indexService)
    {
        $page = $request->query->get('page', 1);
        $limit = 10;

        $searchQuery = $request->query->get("q", null);

        if (is_null($searchQuery)) {
            // Fix search query so that empty matches everything
            $searchQuery = ($searchQuery == "") ? "*" : $searchQuery;

            /** @var Paginator $results */
            $results = $search->search($searchQuery, $page, $limit);
            $resultsCount = $search->searchMaxPagesItems();

            // Fix search display so that a match all (*) is written as an empty string
            $searchQueryDisplay = ($searchQuery == "*") ? "" : $searchQuery;

            $totalPostsReturned = $results->getIterator()->count();
            $totalPosts = $results->count();
            $iterator = $results->getIterator();
            $startPages = 0 < ($page - 10) ? $page - 10 : 1;
            $maxPages = ceil($resultsCount / $limit);
            $maxPagesToDisplay = $maxPages > $page + 10 ? $page + 10 : $maxPages;
            $thisPage = $page;

            $additionalService = [];
            if ($page == 1) {
                $liveSermon = new Event();
                $liveSermon->setId(Uuid::fromString("3beb4e09-8c06-47dd-be47-e06d28dcca7a"));
                $liveSermon->setDate(new \DateTime("next Sunday"));
                $liveSermon->setApm("AM/PM");

                $title = "Watch live";
                if (date('D') == "Sun") {
                    if (date('H') < 10 && date('i') < 30) {
                        $title .= " (starts at 10:30 am)";
                    } elseif (date('H') < 18 && date('i') < 30) {
                        $title .= " (starts at 6:30 pm)";
                    }
                } else {
                    $title .= " this Sunday";
                }

                $liveSermon->setApm("Sunday");
                $liveSermon->setTitle($title);
                $additionalService[] = $liveSermon;
            }

            return $this->render('sermons/index.html.twig', [
                'results' => $results,
                'enablePagination' => true,
                'searchQuery' => $searchQueryDisplay,
                'startPages' => $startPages,
                'maxPages' => $maxPagesToDisplay,
                'maxPagesToDisplay' => $maxPages,
                'thisPage' => $thisPage,
                'liveSermon' => $additionalService,
            ]);
        } else {
            $results = $indexService->search($searchQuery);
            $searchQueryDisplay = ($searchQuery == "*") ? "" : $searchQuery;

            return $this->render('sermons/index.html.twig', [
                'results' => $results,
                'enablePagination' => true,
                'searchQuery' => $searchQueryDisplay,
                'showReset' => true,
            ]);
        }
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
