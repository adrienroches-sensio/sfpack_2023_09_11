<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Movie;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class MovieAddedEvent extends Event
{
    public function __construct(
        public readonly Movie $movie,
        public readonly UserInterface $author,
        public readonly DateTimeImmutable $at,
    ) {
    }
}
