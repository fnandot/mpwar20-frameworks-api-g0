<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class LogRotatingFileFinder implements LogFileFinder
{
    public function __construct(private string $kernelLogsDir)
    {
    }

    /**
     * @return SplFileInfo[]
     */
    public function find(string $environment): iterable
    {
        return (new Finder())
            ->files()
            ->in($this->kernelLogsDir)
            ->name(sprintf('%s-*.json', $environment))
            ->sortByName()
            ->reverseSorting();
    }
}
