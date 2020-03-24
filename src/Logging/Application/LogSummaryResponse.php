<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

final class LogSummaryResponse
{
    /** @var string */
    private $id;

    /** @var string */
    private $environment;

    /** @var string */
    private $level;

    /** @var int */
    private $count;

    public function __construct(string $id, string $environment, string $level, int $count)
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

    public function level(): string
    {
        return $this->level;
    }

    public function count(): int
    {
        return $this->count;
    }
}
