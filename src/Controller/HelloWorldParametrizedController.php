<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HelloWorldParametrizedController
{

    #[Route(
        '/hello/{environment}',
        name: 'hello_parametrized',
        requirements: ['environment' => 'dev|prod|test'],
        methods: ['GET']
    )]
    public function __invoke(string $environment): Response
    {
        return new Response(
            sprintf(
                'Hello world from %s environment',
                $environment
            )
        );
    }
}
