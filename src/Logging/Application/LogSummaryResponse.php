<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use DateTimeImmutable;

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

    /** @var DateTimeImmutable */
    private $updatedOn;

    public function __construct(
        string $id,
        string $environment,
        string $level,
        int $count,
        DateTimeImmutable $updatedOn
    ) {
        $this->id          = $id;
        $this->environment = $environment;
        $this->level       = $level;
        $this->count       = $count;
        $this->updatedOn   = $updatedOn;
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
