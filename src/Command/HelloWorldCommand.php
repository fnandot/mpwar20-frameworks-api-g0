<?php


namespace LaSalle\GroupZero\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
    protected static $defaultName = 'app:hello';

    /** @var string */
    private $environment;

    /** @var string */
    private $environmentAlias;

    public function __construct(string $environment, string $environmentAlias)
    {
        parent::__construct();
        $this->environment      = $environment;
        $this->environmentAlias = $environmentAlias;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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
