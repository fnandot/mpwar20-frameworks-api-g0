<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

final class GetLogEntriesRequest
{
    /** @var string */
    private $environment;

    /** @var int */
    private $page;

    /** @var int */
    private $elementsPerPage;

    public function __construct(string $environment, int $page, int $elements)
    {
        $this->environment = $environment;
        $this->page        = $page;
        $this->elementsPerPage    = $elements;
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
