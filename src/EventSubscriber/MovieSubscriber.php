<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovieSubscriber implements EventSubscriberInterface
{
    public function notifyAllAdmins(MovieAddedEvent $event): void
    {
        dump('TODO : fetch and notify all admins', $event);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieAddedEvent::class => [
                ['notifyAllAdmins', 0]
            ],
        ];
    }
}
