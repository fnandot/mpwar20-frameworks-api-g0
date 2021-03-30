<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class HelloWorldController extends AbstractController
{
    public function helloWorld(): Response
    {
        return new Response(
            sprintf(
                'Hello world from %s (%s) environment',
                getenv('APP_ENV'),
                getenv('APP_ENV_ALIAS')
            )
        );
    }
}
