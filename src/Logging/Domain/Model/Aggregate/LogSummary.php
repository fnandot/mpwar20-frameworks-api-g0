<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Aggregate;

use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class LogSummary
{
    public function __construct(private string $id, private string $environment, private LogLevel $level, private int $count = 0)
    {
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

    public function count(): int
    {
        return $this->count;
    }

    public function increase(): void
    {
        ++$this->count;
    }
}
