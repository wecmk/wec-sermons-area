<?php

namespace App\Controller;

use App\Entity\BibleBooks;
use App\Entity\Event;
use App\Entity\Series;
use App\Repository\BibleBooksRepository;
use App\Repository\EventRepository;
use App\Repository\SeriesRepository;
use App\Services\Books\BooksService;
use App\Services\Event\EventSearchService;
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
    private int $itemsPerPage = 16;
    private $searchAllQuery = "*";

    private BooksService $booksService;
    private EventRepository $eventRepository;
    private SeriesRepository $seriesRepository;


    public function __construct(BooksService $booksService, EventRepository $eventRepository, SeriesRepository $seriesRepository)
    {
        $this->booksService = $booksService;
        $this->eventRepository = $eventRepository;
        $this->seriesRepository = $seriesRepository;
    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param EventSearchService $search
     * @return Responsen
     */
    public function indexAction(Request $request, EventSearchService $search): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $this->itemsPerPage;

        $searchQuery = $request->query->get("q", "");

        // Fix search query so that empty matches everything
        $searchQuery = ($searchQuery == "") ? $this->searchAllQuery : $searchQuery;

        if ($searchQuery == $this->searchAllQuery) {
            $results = $search->findAllWithPagination($page, $limit);
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
                $liveSermon->setId(999999);
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
            $results = $search->search($searchQuery);
            $searchQueryDisplay = ($searchQuery == "*") ? "" : $searchQuery;

            return $this->render('sermons/index.html.twig', [
                'results' => $results,
                'searchQuery' => $searchQueryDisplay,
                'showReset' => true,
            ]);
        }
    }

    /**
     * @Route("/series/{uuid}", name="list_by_series")
     */
    public function searchFieldAction(Request $request, EventSearchService $search, SeriesRepository $seriesRepository, $uuid)
    {
        if (!$uuid instanceof Uuid) {
            $uuid = Uuid::fromString($uuid);
        }
        return $this->render('sermons/index.html.twig', [
                    'results' => $search->searchBySeriesUuid($uuid),
                    'series' => $seriesRepository->findOneBy(['uuid' => $uuid]),
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
        $books = $this->booksService->asBibleNameArray();
        $allSeries = $this->seriesRepository->findAll();

        $seriesFromBibleBooks = [];
        $seriesOther = [];
        foreach ($allSeries as $series) {
            if (in_array($series->getName(), $books)) {
                $seriesFromBibleBooks[] = $series;
            } else {
                $seriesOther[] = $series;
            }
        }

        usort($seriesFromBibleBooks, function(Series $a, Series $b) {
            return $a->getName() <=> $b->getName();
        });

        usort($seriesOther, function(Series $a, Series $b) {
            return $a->getName() <=> $b->getName();
        });


        $visitingSpeakerSeries = $this->seriesRepository->findOneBy(['name' => "Visiting Speaker"]);

        usort($allSeries, 'strcmp');
        return $this->render('sermons/list_of_series.html.twig', [
                    'books' => $seriesFromBibleBooks,
                    'seriesList' => $seriesOther,
                    'visitingSpeakerSeries' => $visitingSpeakerSeries,
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
