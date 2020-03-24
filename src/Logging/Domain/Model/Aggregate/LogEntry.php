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
    /** @var LogEntryId */
    private $id;

    /** @var string */
    private $environment;

    /** @var LogLevel */
    private $level;

    /** @var string */
    private $message;

    /** @var DateTimeImmutable */
    private $occurredOn;

    /** @var DomainEvent[] */
    private $eventStream;

    public function __construct(
        LogEntryId $id,
        string $environment,
        LogLevel $level,
        string $message,
        DateTimeImmutable $occurredOn
    ) {
        $this->id          = $id;
        $this->environment = $environment;
        $this->level       = $level;
        $this->message     = $message;
        $this->occurredOn  = $occurredOn;
    }

    public static function create(
        LogEntryId $id,
        string $environment,
        LogLevel $level,
        string $message,
        DateTimeImmutable $occurredOn
    ): self {
        $instance = new static($id, $environment, $level, $message, $occurredOn);

        $instance->recordThat(
            new LogEntryCreatedDomainEvent(
                (string) $instance->id(),
                $instance->environment(),
                (string) $instance->level(),
                $instance->message(),
                $instance->occurredOn()
            )
        );

        return $instance;
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

    private function recordThat(DomainEvent $event): void
    {
        $this->eventStream[] = $event;
    }
}
