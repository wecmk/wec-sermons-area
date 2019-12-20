<?php

namespace App\Services\Event;

/**
 *
 */
interface EventSearchService
{
    public function search($searchTerm);

    public function searchBySeries($name);

    public function searchBySpeaker($name);
}
