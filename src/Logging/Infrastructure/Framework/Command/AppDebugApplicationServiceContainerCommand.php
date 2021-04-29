<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use LaSalle\GroupZero\Logging\Infrastructure\Services\ApplicationServiceContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AppDebugApplicationServiceContainerCommand extends Command
{
    protected static $defaultName = 'app:debug:application-service-container';

    public function __construct(private ApplicationServiceContainer $applicationServiceContainer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Just to debug Application Service Container')
            ->setHelp('This commands allows you to see all defined Application services');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->applicationServiceContainer->all() as $applicationService) {
            dump($applicationService);
        }

        return 0;
    }
}
