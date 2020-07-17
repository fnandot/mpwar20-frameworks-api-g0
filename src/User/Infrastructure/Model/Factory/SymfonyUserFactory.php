<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Model\Factory;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Factory\UserFactory;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use LaSalle\GroupZero\User\Infrastructure\Model\Aggregate\SymfonyUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class SymfonyUserFactory implements UserFactory
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function register(Email $email, Password $password, UserRole ...$roles): User
    {
        $user = new SymfonyUser(UserId::generate(), $email, $password, ...$roles);

        $encodedPassword = $this
            ->passwordEncoder
            ->encodePassword(
                $user,
                $user->password()
            );

        $user->setPassword($this->buildPasswordWithoutConstructor($encodedPassword));

        return $user;
    }

    /* Workaround to instantiate the Password without constructor (RefClass is slower) */
    private function buildPasswordWithoutConstructor(string $encodedPassword): Password
    {
        return unserialize(
            'O:'.strlen(Password::class).':"'.Password::class.'":1:{s:'.strlen(
                'password'
            ).':"'.'password";s:'.strlen($encodedPassword).':"'.$encodedPassword.'";}'
        );
    }
}
