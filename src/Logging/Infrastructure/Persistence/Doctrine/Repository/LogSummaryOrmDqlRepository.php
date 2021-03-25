<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummaryOrmDqlRepository implements LogSummaryRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function find(LogSummaryId $id): ?LogSummary
    {
        $query = $this
            ->entityManager
            ->createQuery(
                <<<DQL
SELECT ls
FROM LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary ls
WHERE ls.id.id = :id
DQL
            )->setParameters(
                [
                    'id' => $id,
                ]
            );

        return $query
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        $query = $this
            ->entityManager
            ->createQuery(
                <<<DQL
SELECT ls
FROM LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary ls
WHERE ls.environment = :environment
AND ls.level IN (:levels)
DQL
            )
            ->setParameters(
                [
                    'environment' => $environment,
                    'levels' => $levels,
                ]
            );

        return $query
            ->setMaxResults(count($levels))
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary
    {
        $query = $this
            ->entityManager
            ->createQuery(
                <<<DQL
SELECT ls
FROM LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary ls
WHERE ls.environment = :environment
AND ls.level = :level
DQL
            )->setParameters(
                [
                    'environment' => $environment,
                    'level' => $level,
                ]
            );

        return $query
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function save(LogSummary $logSummary): void
    {
        $this->entityManager->persist($logSummary);
        $this->entityManager->flush();
    }
}
