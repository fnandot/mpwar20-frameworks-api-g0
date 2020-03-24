<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use LaSalle\GroupZero\Logging\Application\CreateLogEntry;
use LaSalle\GroupZero\Logging\Application\CreateLogEntryRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateLogEntryController extends AbstractController
{
    /**
     * @Route("/avr/log-entries", methods={"POST"}, requirements={"version"="v1"})
     */
    public function __invoke(CreateLogEntry $createLogEntry, CreateLogEntryRequest $request): Response
    {
        $logEntry = ($createLogEntry)($request);

        return $this->json($logEntry, Response::HTTP_CREATED, [], ['groups' => 'api-v1']);
    }
}
