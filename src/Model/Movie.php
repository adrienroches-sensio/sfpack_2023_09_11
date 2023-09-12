<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Genre as GenreEntity;
use App\Entity\Movie as MovieEntity;
use DateTimeImmutable;
use function array_map;

final class Movie
{
    /**
     * @param list<string> $genres
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly string $plot,
        public readonly string $poster,
        public readonly DateTimeImmutable $releasedAt,
        public readonly array $genres,
    ) {
    }

    public function year(): string
    {
        return $this->releasedAt->format('Y');
    }

    public static function fromEntity(MovieEntity $movieEntity): self
    {
        return new self(
            slug: $movieEntity->getSlug(),
            title: $movieEntity->getTitle(),
            plot: $movieEntity->getPlot(),
            poster: $movieEntity->getPoster(),
            releasedAt: $movieEntity->getReleasedAt(),
            genres: $movieEntity->getGenres()
                                ->map(fn(GenreEntity $genreEntity): string => $genreEntity->getName())
                                ->toArray(),
        );
    }

    /**
     * @param list<MovieEntity> $movieEntities
     *
     * @return list<self>
     */
    public static function fromEntities(array $movieEntities): array
    {
        return array_map(self::fromEntity(...), $movieEntities);
    }
}
