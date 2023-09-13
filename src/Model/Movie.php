<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Genre as GenreEntity;
use App\Entity\Movie as MovieEntity;
use App\Omdb\Client\Model\Movie as MovieOmdb;
use DateTimeImmutable;
use function array_map;
use function explode;
use function str_starts_with;

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
        public readonly Rating $rated,
        public readonly DateTimeImmutable $releasedAt,
        public readonly array $genres,
    ) {
    }

    public function isRemotePoster(): bool
    {
        return str_starts_with($this->poster, 'http');
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
            rated: $movieEntity->getRated(),
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

    public static function fromOmdb(MovieOmdb $movieOmdb): self
    {
        return new self(
            slug: 'todo',
            title: $movieOmdb->Title,
            plot: $movieOmdb->Plot,
            poster: $movieOmdb->Poster,
            rated: Rating::tryFrom($movieOmdb->Rated) ?? Rating::GeneralAudiences,
            releasedAt: new DateTimeImmutable($movieOmdb->Released),
            genres: explode(', ', $movieOmdb->Genre),
        );
    }
}
