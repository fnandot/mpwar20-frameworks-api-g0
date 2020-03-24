<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class FilesystemLogSummaryRepository implements LogSummaryRepository
{
    /** @var string */
    private $directory;

    /** @var string */
    private $defaultEnvironment;

    /** @var Filesystem */
    private $filesystem;

    public function __construct(string $directory, string $defaultEnvironment, Filesystem $filesystem)
    {
        $this->directory          = $directory;
        $this->defaultEnvironment = $defaultEnvironment;
        $this->filesystem         = $filesystem;
    }

    public function find(LogSummaryId $logSummaryId): ?LogSummary
    {
        $files = $this->findFiles($this->defaultEnvironment, ...[]);

        foreach ($files as $file) {
            /** @var LogSummary $summary */
            $summary = unserialize($file->getContents());

            if (((string) $logSummaryId) === $summary->id()) {
                return $summary;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        $files = $this->findFiles($environment, ...$levels);

        /** @var LogSummary[] $summaries */
        $summaries = [];

        foreach ($files as $file) {
            $summaries[] = unserialize($file->getContents());
        }

        return $summaries;
    }

    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary
    {
        $files = $this->findFiles($environment, $level);

        $this->guardOnlyOneLogSummaryByEnvironmentAndLevel($files);

        if (0 === count($files)) {
            return null;
        }

        $fileInfo = reset($files);

        return $this->fromPersistence($fileInfo->getContents());
    }

    public function save(LogSummary $logSummary): void
    {
        $filePath = $this->getFilename($logSummary);

        $this->filesystem->dumpFile($filePath, $this->toPersistence($logSummary));
    }

    private function getFilename(LogSummary $logSummary): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [$this->directory, $logSummary->environment(), $logSummary->level(), $logSummary->id()]
        );
    }

    /**
     * @return SplFileInfo[]
     */
    private function findFiles(string $environment, LogLevel ...$levels): array
    {
        try {
            $files = (new Finder())
                ->in($this->directory.DIRECTORY_SEPARATOR.$environment)
                ->path($levels)
                ->files();
        } catch (DirectoryNotFoundException $e) {
            return [];
        }

        return iterator_to_array($files);
    }

    private function guardOnlyOneLogSummaryByEnvironmentAndLevel($files): void
    {
        if (1 < count($files)) {
            throw new RuntimeException('Hey! By design could not be more than one summary by environment and level!');
        }
    }

    private function toPersistence(LogSummary $logSummary): string
    {
        return serialize($logSummary);
    }

    private function fromPersistence(string $contents): LogSummary
    {
        return unserialize($contents);
    }
}
