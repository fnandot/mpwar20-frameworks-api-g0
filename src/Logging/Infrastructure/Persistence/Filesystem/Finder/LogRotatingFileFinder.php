<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class LogRotatingFileFinder implements LogFileFinder
{
    /** @var string */
    private $kernelLogsDir;

    public function __construct(string $kernelLogsDir)
    {
        $this->kernelLogsDir = $kernelLogsDir;
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
