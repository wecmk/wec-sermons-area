<?php

namespace App\DataProvider;

use App\Entity\Event;
use App\Entity\YouTubeLiveServices;
use App\Repository\EventRepository;

class YouTubeLiveServicesProvider
{
    public function __construct(private readonly EventRepository $eventRepository)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null): array
    {
        $response = new YouTubeLiveServices();
        $response->setId(1);
        $lastSunday = $this->getServices("Last Sunday");
        if (array_key_exists('AM', $lastSunday)) {
            $response->setLastWeekAm($lastSunday['AM']);
        }
        if (array_key_exists('PM', $lastSunday)) {
            $response->setLastWeekPm($lastSunday['PM']);
        }

        $searchText = (date('D') === 'Sun') ? "Today" : "Next Sunday";
        $nextSunday = $this->getServices($searchText);
        if (null != $nextSunday) {
            if (array_key_exists("AM", $nextSunday)) {
                $response->setNextWeekAm($nextSunday['AM']);
            }
            if (array_key_exists("PM", $nextSunday)) {
                $response->setNextWeekPm($nextSunday['PM']);
            }
        }

        return [$response];
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
            if ($event->getYouTubeLink() != null) {
                $response[$event->getApm()] = $event->getYouTubeLink();
            }
        }
        return $response;
    }
}
