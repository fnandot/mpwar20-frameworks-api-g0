<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Monolog\Processor;

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
                'ip'          => $this->randomIpv4($this->generator),
                'http_method' => $this->generator->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
                'server'      => strtolower($this->generator->firstNameFemale).'_'.$this->generator->linuxProcessor,
                'referrer'    => $this->generator->url,
            ]
        );

        return $record;
    }

    private function randomIpv4(Generator $generator): string
    {
        return $generator->randomElement(
            [
                '186.246.40.64',
                '143.45.247.143',
                '66.81.222.19',
                '189.155.35.244',
                '131.133.58.233',
                '197.242.10.38',
                '18.244.170.193',
                '88.46.52.184',
                '18.66.135.48',
                '133.7.105.77',
                '42.31.212.3',
                '165.11.221.178',
                '120.237.69.76',
                '7.200.135.230',
                '204.134.44.231',
            ]
        );
    }
}
