<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\Web;

use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/summary/{environment}", requirements={"environment"="dev|prod"}, name="summary_")
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
        $summaries = ($this->getLogSummariesByEnvironment)($environment);

        return $this->render('summary/list.html.twig', [
            'summaries' => $summaries,
            'environment' => $environment,
        ]);
    }

    /**
     * @Route("/{level}", methods={"GET"}, name="show")
     */
    public function showAction(string $environment, string $level): Response
    {
        $summaries = ($this->getLogSummariesByEnvironment)($environment, $level);

        return $this->render('summary/show.html.twig', [
            'summary' => reset($summaries),
        ]);
    }
}
