<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

class GetLogSummaryRequest
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
