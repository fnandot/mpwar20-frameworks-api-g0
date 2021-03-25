<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

final class GetLogEntriesRequest
{
    public function __construct(private string $environment, private int $page, private int $elementsPerPage)
    {
    }

    public function environment(): string
    {
        return $this->environment;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function elementsPerPage(): int
    {
        return $this->elementsPerPage;
    }
}
