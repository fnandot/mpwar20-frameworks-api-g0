<?php


namespace LaSalle\GroupZero\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
    protected static $defaultName = 'app:hello';

    public function __construct(private string $environment, private string $environmentAlias)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            sprintf(
                'Hello world from %s (%s) environment',
                $this->environment,
                $this->environmentAlias
            )
        );

        return 0;
    }
}
