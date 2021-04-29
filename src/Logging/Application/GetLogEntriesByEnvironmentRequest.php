<?php

namespace LaSalle\GroupZero\Logging\Application;

class GetLogEntriesByEnvironmentRequest
{
    /** @var string[] */
    private array $levels;

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
