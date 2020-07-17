<?php
declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/profile",
     *     "es": "/perfil",
     *     "ru": "/профиль",
     *     "de": "/profil",
     *     "fr": "/profil",
     *     "tr": "/profil"
     * }, name="profile", options = { "utf8": true })
     */
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }
}
