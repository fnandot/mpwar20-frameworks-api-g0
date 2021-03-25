<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironmentRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetCurrentEnvironmentLogSummariesController
{
    /**
     * @Route("/current-env-log-summaries")
     */
    public function __invoke(
        string $kernelEnvironment,
        GetLogSummariesByEnvironment $getLogSummariesByEnvironment,
        SerializerInterface $serializer
    ): JsonResponse {
        $summaries = ($getLogSummariesByEnvironment)(new GetLogSummariesByEnvironmentRequest($kernelEnvironment));

        $serialized = $serializer->serialize($summaries, 'json', ['groups' => 'api']);

        return JsonResponse::fromJsonString($serialized);
    }
}
