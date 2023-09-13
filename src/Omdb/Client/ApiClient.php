<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use App\Omdb\Client\Model\Movie;
use App\Omdb\Client\Model\SearchResult;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class ApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $omdbApiClient,
    ) {
    }

    public function getById(string $imdbId): Movie
    {
        $response = $this->omdbApiClient->request('GET', '/', [
            'query' => [
                'i' => $imdbId,
                'plot' => 'full',
            ],
        ]);

        try {
            /** @var array{Title: string, Year: string, Rated: string, Released: string, Genre: string, Plot: string, Poster: string, imdbID: string, Type: string, Response: string} $movieRaw */
            $movieRaw = $response->toArray(true);
        } catch (Throwable $throwable) {
            throw NoResult::forId($imdbId, $throwable);
        }

        if (array_key_exists('Response', $movieRaw) === true && 'False' === $movieRaw['Response']) {
            throw NoResult::forId($imdbId);
        }

        return new Movie(
            Title: $movieRaw['Title'],
            Year: $movieRaw['Year'],
            Rated: $movieRaw['Rated'],
            Released: $movieRaw['Released'],
            Genre: $movieRaw['Genre'],
            Plot: $movieRaw['Plot'],
            Poster: $movieRaw['Poster'],
            imdbID: $movieRaw['imdbID'],
            Type: $movieRaw['Type'],
        );
    }

    public function searchByTitle(string $title): array
    {
        $response = $this->omdbApiClient->request('GET', '/', [
            'query' => [
                's' => $title,
                'r' => 'json',
                'page' => 1,
                'type' => 'movie',
            ],
        ]);

        try {
            /** @var array{Search: list<array{Title: string, Year: string, imdbID: string, Type: string, Poster: string}>, totalResults: string} $result */
            $result = $response->toArray(true);
        } catch (Throwable $throwable) {
            throw NoResult::searchingForTitle($title, $throwable);
        }

        if (array_key_exists('Response', $result) === true && 'False' === $result['Response']) {
            throw NoResult::searchingForTitle($title);
        }

        if (count($result['Search']) === 0) {
            throw NoResult::searchingForTitle($title);
        }

        return array_map(
            static function (array $rawSearchResult): SearchResult {
                return new SearchResult(
                    Title: $rawSearchResult['Title'],
                    Year: $rawSearchResult['Year'],
                    imdbId: $rawSearchResult['imdbID'],
                    Type: $rawSearchResult['Type'],
                    Poster: $rawSearchResult['Poster'],
                );
            },
            $result['Search']
        );
    }
}
