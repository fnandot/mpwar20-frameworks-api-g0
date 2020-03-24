<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use LaSalle\GroupZero\Logging\Application\Exception\LogSummaryNotFoundException;
use LaSalle\GroupZero\Logging\Application\GetLogSummary;
use LaSalle\GroupZero\Logging\Application\GetLogSummaryRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetLogSummaryController extends AbstractController
{
    /**
     * @Route("/log-summaries/{id}", methods={"GET"}, requirements={"version"="v1"})
     * @Cache(public=true,maxage=30)
     */
    public function __invoke(
        string $id,
        SerializerInterface $serializer,
        Request $request,
        GetLogSummary $getLogSummary
    ): JsonResponse {
        try {
            $summary = ($getLogSummary)(new GetLogSummaryRequest($id));
        } catch (LogSummaryNotFoundException $e) {
            throw new NotFoundHttpException('Resource was not found');
        }

        return $this
            ->json($summary, Response::HTTP_OK, [], ['groups' => 'api-v1'])
            ->setPublic()
            ->setMaxAge(30 /*seconds*/)
        ;
    }
}
