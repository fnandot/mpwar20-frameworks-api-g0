<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;

final class LogSummaryCreatedDomainEvent implements DomainEvent
{
    /** @var string */
    private $aggregateId;

    /** @var string */
    private $environment;

    /** @var string */
    private $level;

    /** @var int */
    private $count;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        string $aggregateId,
        string $environment,
        string $level,
        int $count,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->environment = $environment;
        $this->level       = $level;
        $this->count       = $count;
        $this->occurredOn  = $occurredOn;
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
