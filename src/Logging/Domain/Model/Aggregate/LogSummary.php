<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Aggregate;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogSummaryCreatedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogSummaryIncreasedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummary
{
    /** @var LogSummaryId */
    private $id;

    /** @var string */
    private $environment;

    /** @var LogLevel */
    private $level;

    /** @var int */
    private $count;

    /** @var DomainEvent[] */
    private $eventStream;

    public function __construct(LogSummaryId $id, string $environment, LogLevel $level, int $count = 0)
    {
        $this->id          = $id;
        $this->environment = $environment;
        $this->level       = $level;
        $this->count       = $count;
    }

    public static function create(string $environment, LogLevel $level): self
    {
        $instance = new static(LogSummaryId::generate(), $environment, $level, 0);

        $instance->recordThat(
            new LogSummaryCreatedDomainEvent(
                (string) $instance->id(),
                $instance->environment(),
                (string) $instance->level(),
                $instance->count(),
                new DateTimeImmutable()
            )
        );

        return $instance;
    }

    public function id(): LogSummaryId
    {
        return $this->id;
    }

    public function environment(): string
    {
        return $this->environment;
    }

    public function level(): LogLevel
    {
        return $this->level;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function increase(int $increaseBy = 1): void
    {
        $this->count += $increaseBy;

        $this->recordThat(
            new LogSummaryIncreasedDomainEvent(
                (string) $this->id,
                $increaseBy,
                new DateTimeImmutable()
            )
        );
    }

    /**
     * @return DomainEvent[]
     */
    public function pullDomainEvents(): array
    {
        $events            = $this->eventStream ?: [];
        $this->eventStream = [];

        return $events;
    }

    private function recordThat(DomainEvent $event): void
    {
        $this->eventStream[] = $event;
    }
}
