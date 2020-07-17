<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Domain\Model\Aggregate;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;

abstract class Aggregate
{
    /** @var DomainEvent[] */
    private $eventStream;

    /**
     * @return DomainEvent[]
     */
    public function pullDomainEvents(): array
    {
        $events            = $this->eventStream ?: [];
        $this->eventStream = [];

        return $events;
    }

    protected function recordThat(DomainEvent $event): void
    {
        $this->eventStream[] = $event;
    }
}
