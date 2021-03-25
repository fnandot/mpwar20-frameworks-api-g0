<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use DateTimeImmutable;

final class LogSummaryResponse
{
    public function __construct(
        private string $id,
        private string $environment,
        private string $level,
        private int $count,
        private DateTimeImmutable $updatedOn
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

    public function count(): int
    {
        return $this->count;
    }

    public function updatedOn(): DateTimeImmutable
    {
        return $this->updatedOn;
    }
}
