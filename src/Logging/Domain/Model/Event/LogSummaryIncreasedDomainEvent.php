<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;

final class LogSummaryIncreasedDomainEvent implements DomainEvent
{
    /** @var string */
    private $aggregateId;

    /** @var int */
    private $increasedBy;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        string $aggregateId,
        int $count,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->increasedBy = $count;
        $this->occurredOn  = $occurredOn;
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
