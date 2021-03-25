<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route({
 *     "en": "/home",
 *     "es": "/inicio",
 *     "ru": "/дом",
 *     "de": "/zuhause",
 *     "fr": "/accueil",
 *     "tr": "/ev"
 * }, name="home", options = { "utf8": true })
 */
final class HomeController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('home.html.twig');
    }
}
