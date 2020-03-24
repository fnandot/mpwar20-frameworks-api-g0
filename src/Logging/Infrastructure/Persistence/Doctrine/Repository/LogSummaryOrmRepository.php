<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummaryOrmRepository implements LogSummaryRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return object|LogSummary|null
     */
    public function find(LogSummaryId $logSummaryId): ?LogSummary
    {
        return $this
            ->repository()
            ->find($logSummaryId);
    }

    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        return $this
            ->repository()
            ->findBy(['environment' => $environment, 'level' => $levels]);
    }

    /**
     * @return object|LogSummary|null
     */
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
