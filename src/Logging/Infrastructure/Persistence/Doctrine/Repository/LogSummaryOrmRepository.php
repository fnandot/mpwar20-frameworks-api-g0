<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummaryOrmRepository implements LogSummaryRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function find(LogSummaryId $id): ?LogSummary
    {
        return $this
            ->repository()
            ->find($id);
    }

    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        return $this
            ->repository()
            ->findBy(['environment' => $environment, 'level' => $levels]);
    }

    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary
    {
        return $this
            ->repository()
            ->findOneBy(['environment' => $environment, 'level' => $level]);
    }

    public function save(LogSummary $logSummary): void
    {
        $this->entityManager->persist($logSummary);
        $this->entityManager->flush();
    }

    private function repository(): ObjectRepository
    {
        return $this->entityManager->getRepository(LogSummary::class);
    }
}
