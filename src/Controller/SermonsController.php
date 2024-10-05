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

#[Route(path: '/', name: 'sermons_')]
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
     * @param Request $request
     * @param EventSearchService $search
     * @return Responsen
     */
    #[Route(path: '/', name: 'home')]
    public function index(Request $request, EventSearchService $search): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $this->itemsPerPage;

        $searchQuery = trim($request->query->get("q", ""));

        // Fix search query so that empty matches everything
        $searchQuery = ($searchQuery === "") ? $this->searchAllQuery : $searchQuery;

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

            return $this->render('sermons/index.html.twig', [
                'results' => $results,
                'enablePagination' => true,
                'searchQuery' => $searchQueryDisplay,
                'startPages' => $startPages,
                'maxPages' => $maxPagesToDisplay,
                'maxPagesToDisplay' => $maxPages,
                'thisPage' => $thisPage,
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

    #[Route(path: '/series/{uuid}', name: 'list_by_series')]
    public function searchField(EventSearchService $search, SeriesRepository $seriesRepository, $uuid): \Symfony\Component\HttpFoundation\Response
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

    #[Route(path: '/speaker/{value}', name: 'list_by_speaker')]
    public function searchSpeaker(EventSearchService $search, $value): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('sermons/index.html.twig', [
                    'results' => $search->searchBySpeaker($value),
                    'searchQuery' => "",
        ]);
    }

    #[Route(path: '/listofseries', name: 'list_series')]
    public function ListOfSeries(): \Symfony\Component\HttpFoundation\Response
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
        usort($seriesFromBibleBooks, function (Series $a, Series $b) {
            return $a->getName() <=> $b->getName();
        });
        usort($seriesOther, function (Series $a, Series $b) {
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
