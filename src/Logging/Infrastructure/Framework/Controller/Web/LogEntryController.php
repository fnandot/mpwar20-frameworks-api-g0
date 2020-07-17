<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\Web;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use LaSalle\GroupZero\Logging\Application\GetLogEntries;
use LaSalle\GroupZero\Logging\Application\GetLogEntriesRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_DEVELOPER")
 * @Route(
 *     {
 *      "en": "/entries/{environment}",
 *      "es": "/registros/{environment}",
 *      "ru": "/записи/{environment}",
 *      "de": "/einträge/{environment}",
 *      "fr": "/entrées/{environment}",
 *      "tr": "/girdileri/{environment}"
 *     },
 *     name="entries_",
 *     requirements={"environment"="dev|prod"},
 *     defaults={"environment": "prod"},
 *     options = { "utf8": true }
 * )
 */
final class LogEntryController extends AbstractController
{
    /** @var GetLogEntries */
    private $getLogEntries;

    public function __construct(GetLogEntries $getLogEntries)
    {
        $this->getLogEntries = $getLogEntries;
    }

    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function listAction(string $environment, Request $request, PaginatorInterface $paginator): Response
    {
        $paginatedCollection = ($this->getLogEntries)(
            new GetLogEntriesRequest(
                $environment,
                (int) $request->query->get('page', 1),
                10
            )
        );

        $pagination = $this->transformToSlidingPagination($request, $paginatedCollection);

        return $this->render(
            'logging/entries/list.html.twig',
            [
                'pagination'  => $pagination,
                'environment' => $environment,
            ]
        );
    }

    private function transformToSlidingPagination(Request $request, $paginatedCollection): SlidingPagination
    {
        $pagination = new SlidingPagination([]);

        $pagination->setCurrentPageNumber($paginatedCollection->page());
        $pagination->setItemNumberPerPage($paginatedCollection->elementsPerPage());
        $pagination->setPageRange(5);
        $pagination->setItems($paginatedCollection->elements());
        $pagination->setTotalItemCount($paginatedCollection->totalElements());
        $pagination->setUsedRoute($request->attributes->get('_route'));
        $pagination->setCustomParameters([]);
        $pagination->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');
        $pagination->setCustomParameters(
            [
                'pageParameterName' => 'page',
            ]
        );
        $pagination->setPaginatorOptions([]);

        return $pagination;
    }
}
