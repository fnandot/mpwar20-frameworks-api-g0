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
    /** @var LogSummaryId */
    private $aggregateId;

    /** @var string */
    private $environment;

    /** @var LogLevel */
    private $level;

    /** @var LogCount */
    private $count;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        LogSummaryId $aggregateId,
        string $environment,
        LogLevel $level,
        LogCount $count,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->environment = $environment;
        $this->level       = $level;
        $this->count       = $count;
        $this->occurredOn  = $occurredOn;
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
