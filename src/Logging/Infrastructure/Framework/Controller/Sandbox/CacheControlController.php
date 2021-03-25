<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\Sandbox;

use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CacheControlController
{
    /**
     * @Route("/cache-control/no-store", methods={"GET"})
     */
    public function noCache(): Response
    {
        $response = new Response(
            <<<EOF
Don't cache me! Neither shared (public) caches nor local caches (private)
EOF
        );

        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

    /**
     * @Route("/cache-control/private-expiration", methods={"GET"})
     * @Cache(public=false, expires="2020-03-28T15:00:00+02:00")
     */
    public function privateWithExpiration(): Response
    {
        return (new Response(
            <<<EOF
I am private, so only final clients, like web browsers (private) and not shared caches can cache until Sat, 28 Mar 2020 3PM (GMT+2)
EOF
        )
        )
            ->setPrivate()
            ->setExpires(new DateTimeImmutable('2020-03-28T15:00:00+02:00'));
    }

    /**
     * @Route("/cache-control/relative-expiration", methods={"GET"})
     * @Cache(public=true,maxage=3600)
     */
    public function relativeExpiration(): Response
    {
        return (new Response(
            <<<EOF
Either public or local caches can cache me up to 60 minutes from now.
EOF
        )
        )
            ->setPublic()
            ->setMaxAge(60 /*minutes*/ * 60 /*seconds*/);
    }

    /**
     * @Route("/cache-control/content-validation/etag-forever", methods={"GET"})
     * @Cache(public=true,maxage=5)
     */
    public function etagForever(): Response
    {
        $content = 'I\'ve been set to be cacheable up to 5 seconds, but given that my ETag won\'t change I will be cached forever';
        $etag    = md5($content);

        return (new Response($content))
            ->setPublic()
            ->setEtag($etag)
            ->setMaxAge(5 /*seconds*/);
    }

    /**
     * @Route("/cache-control/content-validation/etag-every-10-seconds", methods={"GET"})
     */
    public function etagEach10Seconds(): Response
    {
        $cachedTime = DateTimeImmutable::createFromFormat('U', (string) (floor(time() / 10) * 10));

        $content = <<<EOF
I've been set to be cacheable up to 5 seconds, but given that my ETag will change every 10 seconds,
really I' will be cached every 10 seconds.
The last time I was cached was on {$cachedTime->format('F j, Y, g:i:s a')}
EOF;

        $etag = md5($content);

        return (new Response($content))
            ->setPublic()
            ->setEtag($etag)
            ->setMaxAge(5 /*seconds*/);
    }
}
