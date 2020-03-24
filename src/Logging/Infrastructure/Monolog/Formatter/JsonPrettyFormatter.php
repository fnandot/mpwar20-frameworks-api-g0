<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use Monolog\Utils;

class JsonPrettyFormatter extends JsonFormatter
{
    protected function toJson($data, $ignoreErrors = false): string
    {
        return Utils::jsonEncode($data, JSON_PRETTY_PRINT, $ignoreErrors);
    }
}
