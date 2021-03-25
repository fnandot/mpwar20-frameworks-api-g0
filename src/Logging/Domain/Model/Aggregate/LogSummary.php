<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Aggregate;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Aggregate\Aggregate;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogSummaryCreatedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogSummaryIncreasedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogCount;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

class LogSummary extends Aggregate
{
    public function __construct(
        private LogSummaryId $id,
        private string $environment,
        private LogLevel $level,
        private ?LogCount $count = null,
        private ?DateTimeImmutable $updatedOn = null
    ) {
        $this->count     = $count ?? LogCount::zero();
        $this->updatedOn = $updatedOn ?? new DateTimeImmutable();
    }

    public static function create(LogSummaryId $id, string $environment, LogLevel $level): self
    {
        $instance = new static($id, $environment, $level, LogCount::zero(), new DateTimeImmutable());

        $instance->recordThat(
            new LogSummaryCreatedDomainEvent(
                $instance->id(),
                $instance->environment(),
                $instance->level(),
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

    public function count(): LogCount
    {
        return $this->count;
    }

    public function updatedOn(): DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function increase(int $increaseBy = 1): void
    {
        $this->count     = $this->count->increaseBy($increaseBy);
        $this->updatedOn = new DateTimeImmutable();

        $this->recordThat(
            new LogSummaryIncreasedDomainEvent(
                $this->id,
                $increaseBy,
                new DateTimeImmutable()
            )
        );
    }
}
