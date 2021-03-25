<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;

class FakeWebProcessor implements ProcessorInterface
{
    public function __invoke(array $record): array
    {
        $record['extra'] = array_merge(
            $record['extra'],
            [
                'url' => 'https://www.salleurl.edu/en',
                'ip' => '197.228.0.32',
                'http_method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'][random_int(0, 4)],
                'server' => sha1(random_bytes(5)),
                'referrer' => 'https://www.salleurl.edu/en',
            ]
        );

        return $record;
    }
}
