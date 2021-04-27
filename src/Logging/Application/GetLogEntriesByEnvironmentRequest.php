<?php

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Core\Application\Query;

class GetLogEntriesByEnvironmentRequest implements Query
{
    /** @var string[] */
    private $levels;

    public function __construct(private string $environment, string ...$levels)
    {
        $this->levels = $levels;
    }

    public function environment(): string
    {
        return $this->environment;
    }

    /**
     * @return string[]
     */
    public function levels(): array
    {
        return $this->levels;
    }
}
