<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogCount;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummaryCreatedDomainEvent implements DomainEvent
{
    public function __construct(
        private LogSummaryId $aggregateId,
        private string $environment,
        private LogLevel $level,
        private LogCount $count,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public function aggregateId(): LogSummaryId
    {
        return $this->aggregateId;
    }

    public function environment(): string
    {
        return $this->environment;
    }

    public function level(): LogLevel
    {
        return $this->level;
    }

    public function count(): LogCount
    {
        return $this->count;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
