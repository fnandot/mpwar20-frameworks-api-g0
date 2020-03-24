<?php

namespace LaSalle\GroupZero\Logging\Application;

class GetLogEntriesByEnvironmentRequest
{
    /** @var string */
    private $environment;

    /** @var string[] */
    private $levels;

    public function __construct(string $environment, string ...$levels)
    {
        $this->environment = $environment;
        $this->levels      = $levels;
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
