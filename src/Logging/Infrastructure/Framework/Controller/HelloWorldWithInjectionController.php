<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HelloWorldWithInjectionController extends AbstractController
{
    private const LOG_ERROR_QUERY_PARAM = 'logError';

    public function __construct(private string $environment, private string $environmentAlias)
    {
    }

    public function __invoke(
        LoggerInterface $logger,
        Request $request
    ): Response {
        $logger->info(
            'Hi! This an info level log from {environment} environment',
            [
                'environment' => $this->environment,
                'client_ips'  => $request->getClientIps(),
            ]
        );

        $logger->warning(
            'Hi! This a warning level log from {environment} environment',
            [
                'environment' => $this->environment,
                'client_ips'  => $request->getClientIps(),
            ]
        );

        if ($request->query->has(static::LOG_ERROR_QUERY_PARAM)) {
            $logger->error(
                'A request with {parameter} was made!',
                [
                    'parameter'  => static::LOG_ERROR_QUERY_PARAM,
                    'client_ips' => $request->getClientIps(),
                ]
            );
        }

        return new Response(
            sprintf(
                'Hello world from %s (%s) environment',
                $this->environmentAlias,
                $this->environment
            )
        );
    }
}
