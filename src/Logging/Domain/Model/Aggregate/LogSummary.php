<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Aggregate;

use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class LogSummary
{
    /** @var string */
    private $id;

    /** @var string */
    private $environment;

    /** @var LogLevel */
    private $level;

    /** @var int */
    private $count;

    public function __construct(string $id, string $environment, LogLevel $level, int $count = 0)
    {
        $this->id          = $id;
        $this->environment = $environment;
        $this->level       = $level;
        $this->count       = $count;
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
