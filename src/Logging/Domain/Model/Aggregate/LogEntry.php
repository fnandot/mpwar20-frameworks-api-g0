<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Aggregate;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogEntryCreatedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogEntryId;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class LogEntry
{
    /** @var DomainEvent[] */
    private array $eventStream;

    public function __construct(
        private LogEntryId $id,
        private string $environment,
        private LogLevel $level,
        private string $message,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function create(
        LogEntryId $id,
        string $environment,
        LogLevel $level,
        string $message,
        DateTimeImmutable $occurredOn
    ): self {
        $instance = new LogEntry($id, $environment, $level, $message, $occurredOn);

        $instance->recordThat(
            new LogEntryCreatedDomainEvent(
                (string)$instance->id(),
                $instance->environment(),
                (string)$instance->level(),
                $instance->message(),
                $instance->occurredOn()
            )
        );

        return $instance;
    }

    private function recordThat(DomainEvent $event): void
    {
        $this->eventStream[] = $event;
    }

    public function id(): LogEntryId
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

    public function message(): string
    {
        return $this->message;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function pullDomainEvents(): array
    {
        $events            = $this->eventStream ?: [];
        $this->eventStream = [];

        return $events;
    }
}
