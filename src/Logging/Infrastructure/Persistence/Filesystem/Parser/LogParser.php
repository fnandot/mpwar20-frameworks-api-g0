<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Parser;

interface LogParser
{
    public function parse(string $rawLog): array;
}
