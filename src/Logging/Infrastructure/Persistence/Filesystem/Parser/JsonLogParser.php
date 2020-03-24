<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Parser;

final class JsonLogParser implements LogParser
{
    public function parse(string $rawLog): array
    {
        return json_decode($rawLog, true);
    }
}
