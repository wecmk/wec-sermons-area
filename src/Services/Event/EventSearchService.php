<?php

namespace App\Services\Event;

/**
 *
 */
interface EventSearchService
{
    public function search($searchTerm, $page, $limit);

    public function searchBySeries($name);

    public function searchBySpeaker($name);
}
