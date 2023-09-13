<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final class NoResult extends Exception implements HttpExceptionInterface
{
    private function __construct(
        string $message = '',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $this->getStatusCode(), $previous);
    }

    public function getStatusCode(): int
    {
        return 404;
    }

    public function getHeaders(): array
    {
        return [];
    }

    public static function forId(string $imdbId, ?Throwable $previous = null): self
    {
        return new self("No movie found on OMDB API for IMDB ID '{$imdbId}'.", $previous);
    }
}
