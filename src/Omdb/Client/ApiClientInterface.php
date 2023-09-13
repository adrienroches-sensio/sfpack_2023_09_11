<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use App\Omdb\Client\Model\Movie;
use App\Omdb\Client\Model\SearchResult;

interface ApiClientInterface
{
    /**
     * @throws NoResult When the $imdbId was not found.
     */
    public function getById(string $imdbId): Movie;

    /**
     * @return list<SearchResult>
     *
     * @throws NoResult When the $title returned no result.
     */
    public function searchByTitle(string $title): array;
}
