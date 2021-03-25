<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\PaginatedLogEntryCollection;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\Pagination;

final class LogEntryDoctrineRepository implements LogEntryRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findByEnvironmentPaginated(string $environment, Pagination $pagination): PaginatedLogEntryCollection
    {
        $qb = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('le')
            ->from(LogEntry::class, 'le')
            ->where('le.environment = :environment')
            ->setParameter('environment', $environment)
            ->setFirstResult(($pagination->page() - 1) * $pagination->elementsPerPage())
            ->setMaxResults($pagination->elementsPerPage());

        $paginator = (new Paginator($qb));

        return new PaginatedLogEntryCollection(
            $pagination->page(),
            $pagination->elementsPerPage(),
            $paginator->count(),
            ...$paginator->getIterator()->getArrayCopy()
        );
    }

    public function findAllByEnvironment(string $environment, LogLevel ...$levels): array
    {
        return $this->repository()->findBy(['environment' => $environment]);
    }

    public function save(LogEntry $logEntry): void
    {
        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();
    }

    private function repository(): ObjectRepository
    {
        return $this->entityManager->getRepository(LogEntry::class);
    }
}
