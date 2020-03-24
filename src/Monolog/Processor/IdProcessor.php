<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;
use Ramsey\Uuid\Uuid;

final class IdProcessor implements ProcessorInterface
{
    public function __invoke(array $record)
    {
        $record['extra']['id'] = Uuid::uuid4()->toString();

        return $record;
    }
}
