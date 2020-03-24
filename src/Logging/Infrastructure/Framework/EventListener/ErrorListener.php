<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $format  = $request->attributes->get('_format');

        if ('json' !== $format || 'v1' !== $request->attributes->get('version')) {
            return;
        }

        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response
                ->setData(
                    [
                        'error'   => $exception->getStatusCode(),
                        'message' => $exception->getMessage(),
                    ]
                );
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response
                ->setData(
                    [
                        'error'   => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => 'An unexpected error occurred.',
                    ]
                );
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
