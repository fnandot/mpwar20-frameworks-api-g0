<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use LaSalle\GroupZero\Logging\Application\LogSummaryResponse;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetLogSummaryStubController extends AbstractController
{
    /**
     * @Route("/log-summaries/{id}", methods={"GET"}, requirements={"version"="v1"})
     * @Cache(public=true,maxage=30)
     */
    public function __invoke(
        SerializerInterface $serializer,
        Request $request,
        string $id,
        string $kernelEnvironment
    ): JsonResponse {
        $logSummary = new LogSummaryResponse(
            $id,
            $kernelEnvironment,
            LogLevel::INFO,
            10
        );

        return $this
            ->json($logSummary, Response::HTTP_OK, [], ['groups' => 'api-v1']);
    }
}
