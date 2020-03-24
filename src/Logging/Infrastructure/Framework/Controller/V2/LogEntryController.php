<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V2;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use JMS\Serializer\SerializerInterface;
use LaSalle\GroupZero\Logging\Application\CreateLogEntry;
use LaSalle\GroupZero\Logging\Application\CreateLogEntryRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

/**
 * @RouteResource("log-entries")
 */
final class LogEntryController extends AbstractFOSRestController
{
    /** @var CreateLogEntry */
    private $createLogEntry;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(CreateLogEntry $createLogEntry, SerializerInterface $serializer)
    {
        $this->createLogEntry = $createLogEntry;
        $this->serializer     = $serializer;
    }

    public function postAction(Request $request): Response
    {
        $data = $request->getContent();

        try {
            $createLogEntryRequest = $this
                ->serializer
                ->deserialize($data, CreateLogEntryRequest::class, 'json');
        } catch (Throwable $t) {
            throw new BadRequestHttpException('Bad request', $t);
        }

        $logEntry = ($this->createLogEntry)($createLogEntryRequest);

        return new Response(
            null,
            Response::HTTP_CREATED
        );
    }
}
