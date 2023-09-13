<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use App\Omdb\Client\Model\Movie;
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
}
