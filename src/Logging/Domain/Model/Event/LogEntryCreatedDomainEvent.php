<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;

final class LogEntryCreatedDomainEvent implements DomainEvent
{
    public function __construct(
        private string $aggregateId,
        private string $environment,
        private string $level,
        private string $message,
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

    public function message(): string
    {
        return $this->message;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
