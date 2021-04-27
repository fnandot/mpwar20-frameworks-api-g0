<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class AppDemoBar extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static string $defaultName = 'app:demo:bar';

    protected function configure(): void
    {
        $this
            ->setDescription('Demo bar');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $section1 = $output->section();

        $progress1 = new ProgressBar($section1);
        $progress1->setFormat(
            '%message% %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%'
        );
        $progress1->setMessage('Loading log entries...');

        $progress1->start(100);

        while ($progress1->getProgress() < 100) {
            usleep(random_int(0, $progress1->getProgress()) * 1500);
            $progress1->advance();
        }

        $output->writeln('Loaded successfully');

        return 0;
    }
}
