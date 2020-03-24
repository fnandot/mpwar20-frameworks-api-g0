<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Infrastructure\Event;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerDomainEventBus implements DomainEventBus
{
    /** @var MessageBusInterface */
    private $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
