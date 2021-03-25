<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Reader;

use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Parser\LogParser;
use SplFileInfo;

final class LogFileReader
{
    public function __construct(private LogParser $parser)
    {
    }

    /**
     * @param SplFileInfo[] $files
     */
    public function read(iterable $files): array
    {
        $entries = [];

        foreach ($files as $file) {
            $file = fopen($file->getRealPath(), 'rb');
            while ($line = fgets($file)) {
                $entries[] = $this->parser->parse($line);
            }
            fclose($file);
        }

        return $entries;
    }
}
