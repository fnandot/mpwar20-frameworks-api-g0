<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use LaSalle\GroupZero\Logging\Application\CreateLogEntry;
use LaSalle\GroupZero\Logging\Application\CreateLogEntryRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateLogEntryController
{
    /** @var CreateLogEntry */
    private $createLogEntry;

    public function __construct(CreateLogEntry $getLogSummariesByEnvironment)
    {
        $this->createLogEntry = $getLogSummariesByEnvironment;
    }

    /**
     * @Route("/avr/log-entries", methods={"POST"}, requirements={"version"="v1"})
     */
    public function __invoke(CreateLogEntryRequest $request): Response
    {
        ($this->createLogEntry)($request);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
