<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder;

use SplFileInfo;

interface LogFileFinder
{
    /**
     * @return SplFileInfo[]
     */
    public function find(string $environment): iterable;
}
