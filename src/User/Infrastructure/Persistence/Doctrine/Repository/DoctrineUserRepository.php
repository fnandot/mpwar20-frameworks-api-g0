<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\Repository\UserRepository;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Infrastructure\Model\Aggregate\SymfonyUser;

final class DoctrineUserRepository implements UserRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOne(UserId $userId): ?User
    {
        return $this->repository()->find($userId);
    }

    public function findOneByEmail(Email $email): ?User
    {
        return $this->repository()->findOneBy(['email.email' => (string) $email]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function repository(): ObjectRepository
    {
        return $this->entityManager->getRepository(SymfonyUser::class);
    }
}
