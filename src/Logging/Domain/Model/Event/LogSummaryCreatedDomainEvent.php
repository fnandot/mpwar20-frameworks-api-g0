<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;

final class LogSummaryCreatedDomainEvent implements DomainEvent
{
    public function __construct(
        private string $aggregateId,
        private string $environment,
        private string $level,
        private int $count,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function environment(): string
    {
        return $this->environment;
    }

    public function level(): string
    {
        return $this->level;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
