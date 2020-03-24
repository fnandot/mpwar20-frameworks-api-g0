<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironmentRequest;
use LaSalle\GroupZero\Logging\Domain\Model\Exception\InvalidLogLevelException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetLogSummariesController extends AbstractController
{
    /**
     * @Route("/log-summaries", methods={"GET"}, requirements={"version"="v1"})
     */
    public function __invoke(
        SerializerInterface $serializer,
        Request $request,
        GetLogSummariesByEnvironment $getLogSummariesByEnvironment
    ): JsonResponse {
        if (!$request->query->has('env')) {
            throw new BadRequestHttpException('No environment supplied');
        }

        $env = $request->query->get('env');
        $levels = $request->query->get('level', []);

        try {
            $summaries = ($getLogSummariesByEnvironment)(new GetLogSummariesByEnvironmentRequest($env, ...$levels));
        } catch (InvalidLogLevelException $e) {
            throw new BadRequestHttpException('Provided log level is not valid!');
        }

        return $this
            ->json($summaries, Response::HTTP_OK, [], ['groups' => 'api-v1'])
        ;
    }
}
