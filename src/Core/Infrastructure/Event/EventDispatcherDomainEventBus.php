<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Infrastructure\Event;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherDomainEventBus implements DomainEventBus
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function publish(DomainEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
