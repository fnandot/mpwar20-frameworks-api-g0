<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummaryIncreasedDomainEvent implements DomainEvent
{
    public function __construct(
        private LogSummaryId $aggregateId,
        private int $increasedBy,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public function aggregateId(): LogSummaryId
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
