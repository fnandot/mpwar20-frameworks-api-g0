<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Reader;

use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Parser\LogParser;
use SplFileInfo;

final class LogFileReader
{
    /** @var LogParser */
    private $parser;

    public function __construct(LogParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param SplFileInfo[] $files
     */
    public function read(iterable $files): array
    {
        $entries = [];

        foreach ($files as $file) {
            $file = fopen($file->getRealPath(), 'r');
            while ($line = fgets($file)) {
                $entries[] = $this->parser->parse($line);
            }
            fclose($file);
        }

        return $entries;
    }
}
