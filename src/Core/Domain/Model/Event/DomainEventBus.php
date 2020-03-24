<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Domain\Model\Event;

interface DomainEventBus
{
    public function publish(DomainEvent $event): void;
}
