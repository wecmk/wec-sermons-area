<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\AttachmentMetadata;
use App\Entity\Event;
use App\Entity\YouTubeLiveServices;
use App\Entity\PublicSermon;
use App\Repository\EventRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class YouTubeLiveServicesProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface {

    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getCollection(string $resourceClass, string $operationName = null): YouTubeLiveServices
    {
        $response = new YouTubeLiveServices();
        $response->setId(1);
        $lastSunday = $this->getServices("Last Sunday");
        $response->setLastWeekAm($lastSunday['AM']);
        $response->setLastWeekPm($lastSunday['PM']);

        $searchText = (date('D') == 'Sun') ? "Today" : "Next Sunday";
        $nextSunday = $this->getServices($searchText);
        if (null != $nextSunday) {
            if (array_key_exists("AM", $nextSunday)) {
                $response->setNextWeekAm($nextSunday['AM']);
            }
            if (array_key_exists("PM", $nextSunday)) {
                $response->setNextWeekPm($nextSunday['PM']);
            }
        }

        return $response;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === YouTubeLiveServices::class;
    }

    /**
     * Returns an array
     * array[AM] = link to service
     * array[PM] = link to service
     *
     * or [] is null if no link provided
     *
     * @param $search
     * @return array|null
     */
    private function getServices($search): array
    {
        $lastSundayServices = $this->eventRepository->servicesByDate($search);

        $response = [];
        /** @var Event $event */;
        foreach ($lastSundayServices as $event) {
            foreach ($event->getEventUrls() as $url) {
                if ($url->getTitle() == "Watch") {
                    $response[$url->getEvent()->getApm()] = $url->getUrl();
                }
            }
        }
        return $response;
    }
}
