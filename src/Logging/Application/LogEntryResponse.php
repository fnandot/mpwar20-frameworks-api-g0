<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use DateTimeImmutable;

final class LogEntryResponse
{
    public function __construct(
        private string $id,
        private string $environment,
        private string $level,
        private string $message,
        private DateTimeImmutable $occurredOn
    ) {
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
