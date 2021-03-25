<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V2;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironmentRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("log-summaries")
 */
final class LogSummaryController extends AbstractFOSRestController
{
    public function __construct(private GetLogSummariesByEnvironment $getLogSummariesByEnvironment)
    {
    }

    /**
     * @QueryParam(
     *   name="level",
     *   requirements="(emergency|alert|critical|error|warning|notice|info|debug)",
     *   allowBlank=false,
     *   map=true,
     *   strict=true,
     *   nullable=true,
     *   description="The levels you want to get summaries",
     * )
     * @QueryParam(
     *     name="env",
     *     requirements="(prod|dev)",
     *     allowBlank=false,
     *     strict=true,
     *     nullable=false,
     *     description="The environment of the summaries"
     * )
     */
    public function cgetAction(ParamFetcher $paramFetcher, Request $request): Response
    {
        $environment = $paramFetcher->get('env');
        $levels      = $paramFetcher->get('level') ?: [];

        $data = ($this->getLogSummariesByEnvironment)(
            new GetLogSummariesByEnvironmentRequest($environment, ...$levels)
        );

        return $this->handleView($this->view($data));
    }
}
