<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use DateTimeImmutable;

final class CreateLogEntryRequest
{
    /** @var string */
    private $id;

    /** @var string */
    private $environment;

    /** @var string */
    private $level;

    /** @var string */
    private $message;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        string $id,
        string $environment,
        string $level,
        string $message,
        DateTimeImmutable $occurredOn
    ) {
        $this->id = $id;
        $this->environment = $environment;
        $this->level = $level;
        $this->message = $message;
        $this->occurredOn = $occurredOn;
    }

    public function id(): string
    {
        return $this->id;
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
