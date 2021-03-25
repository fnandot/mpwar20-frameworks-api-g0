<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\Sandbox;

use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class ContentNegotiationController
{
    /**
     * @Route("/content-negotiation/json-only", methods={"GET"})
     */
    public function acceptJsonOnly(Request $request): Response
    {
        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));
        if ($acceptHeader->has('application/json')) {
            return $this->handleJson();
        }

        throw new NotAcceptableHttpException();
    }

    /**
     * @Route("/content-negotiation/xml-and-json-only", methods={"GET"})
     */
    public function acceptXmlAndJsonOnly(Request $request): Response
    {
        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));

        // Headers are sored by their quality
        foreach ($acceptHeader->all() as $acceptHeaderItem) {
            switch (true) {
                case 'application/xml' === $acceptHeaderItem->getValue():
                    return $this->handleXml();
                case 'application/json' === $acceptHeaderItem->getValue():
                    return $this->handleJson();
            }
        }

        throw new NotAcceptableHttpException();
    }

    /**
     * @Route("/content-negotiation/xml-json-and-html", methods={"GET"})
     */
    public function acceptXmlJsonAndHtmlOnly(Request $request): Response
    {
        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));

        // Headers are sored by their quality
        foreach ($acceptHeader->all() as $acceptHeaderItem) {
            switch (true) {
                case 'application/xml' === $acceptHeaderItem->getValue():
                    return $this->handleXml();
                case 'application/json' === $acceptHeaderItem->getValue():
                    return $this->handleJson();
                case 'text/html' === $acceptHeaderItem->getValue():
                    return $this->handleHtml();
            }
        }

        throw new NotAcceptableHttpException();
    }

    /**
     * @Route("/content-negotiation/all", methods={"GET"})
     */
    public function acceptAll(Request $request): Response
    {
        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));

        // Headers are sored by their quality
        foreach ($acceptHeader->all() as $acceptHeaderItem) {
            switch (true) {
                case 'application/xml' === $acceptHeaderItem->getValue():
                    return $this->handleXml();
                case 'application/json' === $acceptHeaderItem->getValue():
                    return $this->handleJson();
                case 'text/html' === $acceptHeaderItem->getValue():
                    return $this->handleHtml();
                case '*/*' === $acceptHeaderItem->getValue():
                    return $this->handleText();
            }
        }

        throw new NotAcceptableHttpException();
    }

    private function handleText(): Response
    {
        $response = new Response('Hello from plain text');
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

    private function handleHtml(): Response
    {
        $response = new Response(
            <<<HTML
<!DOCTYPE html>
<html>
<body>

<h1>Hello from HTML</h1>

</body>
</html>
HTML
        );
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    private function handleXml(): Response
    {
        $response = new Response(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<data>
  <message>Hello in XML!</message>
</data>
XML
        );
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }

    private function handleJson(): Response
    {
        $data = json_encode(
            [
                'data' => [
                    'message' => 'Hello in JSON format',
                ],
            ]
        );

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
