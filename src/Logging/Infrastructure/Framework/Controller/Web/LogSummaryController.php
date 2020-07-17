<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\Web;

use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironmentRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     {
 *      "en": "/summary/{environment}",
 *      "es": "/sumario/{environment}",
 *      "ru": "/резюме/{environment}",
 *      "de": "/zusammenfassung/{environment}",
 *      "fr": "/sommaire/{environment}",
 *      "tr": "/özet/{environment}"
 *     },
 *     name="summary_",
 *     requirements={"environment"="dev|prod"},
 *     defaults={"environment": "prod"},
 *     options = { "utf8": true }
 * )
 */
final class LogSummaryController extends AbstractController
{
    /** @var GetLogSummariesByEnvironment */
    private $getLogSummariesByEnvironment;

    public function __construct(GetLogSummariesByEnvironment $getLogSummariesByEnvironment)
    {
        $this->getLogSummariesByEnvironment = $getLogSummariesByEnvironment;
    }

    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function listAction(string $environment): Response
    {
        $summaries = ($this->getLogSummariesByEnvironment)(new GetLogSummariesByEnvironmentRequest($environment));

        return $this->render(
            'logging/summary/list.html.twig',
            [
                'summaries'   => $summaries,
                'environment' => $environment,
            ]
        );
    }

    /**
     * @Route("/{level}", methods={"GET"}, name="show")
     */
    public function showAction(string $environment, string $level): Response
    {
        $summaries = ($this->getLogSummariesByEnvironment)(
            new GetLogSummariesByEnvironmentRequest($environment, $level)
        );

        return $this->render(
            'logging/summary/show.html.twig',
            [
                'summary' => reset($summaries),
            ]
        );
    }
}
