<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Aggregate;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Aggregate\Aggregate;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogEntryCreatedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use Ramsey\Uuid\Uuid;

class LogEntry extends Aggregate
{
    /** @var string */
    private $id;

    /** @var string */
    private $environment;

    /** @var LogLevel */
    private $level;

    /** @var string */
    private $message;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        string $id,
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
        string $id,
        string $environment,
        LogLevel $level,
        string $message,
        DateTimeImmutable $occurredOn
    ): self {
        $instance = new static($id, $environment, $level, $message, $occurredOn);

        $instance->recordThat(
            new LogEntryCreatedDomainEvent(
                $instance->id(),
                $instance->environment(),
                (string) $instance->level(),
                $instance->message(),
                $instance->occurredOn()
            )
        );

        return $instance;
    }

    public function id(): string
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
}
