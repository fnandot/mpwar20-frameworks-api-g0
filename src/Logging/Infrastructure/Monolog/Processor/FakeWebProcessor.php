<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Monolog\Processor;

use Faker\Factory;
use Faker\Generator;
use Monolog\Processor\ProcessorInterface;

class FakeWebProcessor implements ProcessorInterface
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = $generator = Factory::create();
    }

    public function __invoke(array $record)
    {
        $record['extra'] = array_merge(
            $record['extra'],
            [
                'url'         => $this->generator->url,
                'ip'          => $this->generator->ipv4,
                'http_method' => $this->generator->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
                'server'      => strtolower($this->generator->firstNameFemale).'_'.$this->generator->linuxProcessor,
                'referrer'    => $this->generator->url,
            ]
        );

        return $record;
    }
}
