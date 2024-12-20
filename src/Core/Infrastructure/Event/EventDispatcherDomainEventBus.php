<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Infrastructure\Event;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherDomainEventBus implements DomainEventBus
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
