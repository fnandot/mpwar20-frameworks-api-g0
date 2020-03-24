<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class LogRotatingFileFinder implements LogFileFinder
{
    /** @var string */
    private $directory;

    public function __construct(string $logDirectory)
    {
        $this->directory = $logDirectory;
    }

    /**
     * @return SplFileInfo[]
     */
    public function find(string $environment): iterable
    {
        return (new Finder())
            ->files()
            ->in($this->directory)
            ->name(sprintf('%s-*.log', $environment))
            ->sortByName()
            ->reverseSorting();
    }
}
