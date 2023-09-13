<?php

declare(strict_types=1);

namespace App\Omdb\Bridge;

use App\Entity\Movie as MovieEntity;
use App\Omdb\Client\Model\Movie as MovieOmdb;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

final class DatabaseImporter
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly EntityManagerInterface $entityManager,
        private readonly MovieRepository $movieRepository,
        private readonly GenreRepository $genreRepository,
    ) {
    }

    public function import(MovieOmdb $movieOmdb, bool $flush = false): MovieEntity
    {
        $slug = $this->slugger->slug("{$movieOmdb->Year}-{$movieOmdb->Title}")->toString();

        $movieEntity = (new MovieEntity())
            ->setTitle($movieOmdb->Title)
            ->setPlot($movieOmdb->Plot)
            ->setPoster($movieOmdb->Poster)
            ->setReleasedAt(new DateTimeImmutable($movieOmdb->Released))
            ->setSlug($slug)
        ;

        foreach (explode(', ', $movieOmdb->Genre) as $genreName) {
            $movieEntity->addGenre($this->genreRepository->get($genreName));
        }

        try {
            $this->entityManager->persist($movieEntity);

            if (true === $flush) {
                $this->entityManager->flush();
            }
        } catch (UniqueConstraintViolationException) {
            return $this->movieRepository->getBySlug($slug);
        }

        return $movieEntity;
    }
}
