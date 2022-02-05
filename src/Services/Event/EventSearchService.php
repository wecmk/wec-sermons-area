<?php

namespace App\Services\Event;

/**
 *
 */
interface EventSearchService
{
    public function findAllWithPagination($page, $limit);

    public function search($searchTerm);

    public function searchMaxPagesItems();

    public function searchBySeries($name);

    public function searchBySeriesUuid($uuid);

    public function searchBySpeaker($name);
}
