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
                $_ENV['APP_ENV'],
                $_ENV['APP_ENV_ALIAS']
            )
        );
    }
}
