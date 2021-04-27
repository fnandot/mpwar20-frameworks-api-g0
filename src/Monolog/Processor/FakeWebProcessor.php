<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;

class FakeWebProcessor implements ProcessorInterface
{
    private static array $httpMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    private static array $ips = [
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
    ];

    public function __invoke(array $record): array
    {
        $record['extra'] = array_merge(
            $record['extra'],
            [
                'url'         => 'https://salle.url.edu',
                'ip'          => $this->randomIpv4(),
                'http_method' => $this->randomHttpMethod(),
                'server'      => 'playground',
                'referrer'    => 'https://salle.url.edu',
            ]
        );

        return $record;
    }

    private function randomIpv4(): string
    {
        return static::$ips[array_rand(static::$ips)];
    }

    private function randomHttpMethod(): string
    {
        return static::$httpMethods[array_rand(static::$httpMethods)];
    }
}
