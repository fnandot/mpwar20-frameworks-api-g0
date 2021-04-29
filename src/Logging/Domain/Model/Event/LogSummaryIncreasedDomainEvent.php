<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;

final class LogSummaryIncreasedDomainEvent implements DomainEvent
{
    public function __construct(
        private string $aggregateId,
        private int $increasedBy,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function increasedBy(): int
    {
        return $this->increasedBy;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
