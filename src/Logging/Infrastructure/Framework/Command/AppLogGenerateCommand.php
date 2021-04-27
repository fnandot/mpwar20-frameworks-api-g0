<?php

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use Faker\Factory;
use Faker\Generator;
use LaSalle\GroupZero\Logging\Infrastructure\Monolog\Exception\Fake\CouldNotCreateUserException;
use LaSalle\GroupZero\Logging\Infrastructure\Monolog\Exception\Fake\UserNotFoundException;
use LaSalle\GroupZero\Logging\Infrastructure\Monolog\Processor\FakeWebProcessor;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Bundle\MakerBundle\Event\ConsoleErrorSubscriber;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppLogGenerateCommand extends Command
{
    protected static string $defaultName = 'app:log:generate';

    protected static array $logLevels = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    public function __construct(private LoggerInterface $logger)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Random log generator')
            ->addOption(
                'iterations',
                'i',
                InputOption::VALUE_REQUIRED,
                'Number of logs to generate',
                1000
            )
            ->addOption(
                'delay',
                'd',
                InputOption::VALUE_REQUIRED,
                'Time in microseconds between iterations',
                0
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $generator = Factory::create();

        $delay = (int) $input->getOption('delay');

        /* Adding this FakeProcessor only this command */
        $this->logger->pushProcessor(new FakeWebProcessor());

        for ($i = (int) $input->getOption('iterations'); $i > 0; --$i) {
            $logData = $this->randomLog($generator);

            $this->logger->log($logData['level'], $logData['message'], $logData['context']);

            usleep($delay);
        }

        return 0;
    }

    private function randomLog(Generator $generator): array
    {
        return $generator
            ->randomElement(
                [
                    [
                        'level'   => LogLevel::NOTICE,
                        'message' => 'User identified by "{user_id}" was not created due "{reason}"!',
                        'context' => [
                            'user_id'   => (string) Uuid::uuid4(),
                            'reason'    => $generator->sentence(),
                            'exception' => new CouldNotCreateUserException(),
                        ],
                    ],
                    [
                        'level'   => LogLevel::INFO,
                        'message' => 'User identified by "{user_id}" could not be found"!',
                        'context' => [
                            'user_id'   => (string) Uuid::uuid4(),
                            'exception' => new UserNotFoundException(),
                        ],
                    ],
                    [
                        'level'   => LogLevel::INFO,
                        'message' => 'Added user role "{role}" to user identified by "{user_id}"',
                        'context' => [
                            'user_id' => (string) Uuid::uuid4(),
                            'role'    => 'admin',
                        ],
                    ],
                    [
                        'level'   => LogLevel::CRITICAL,
                        'message' => sprintf(
                            'Uncaught PHP Exception %s: "%s" at %s line %s',
                            'PDOException',
                            'Could not connect to database.',
                            __FILE__,
                            __LINE__
                        ),
                        'context' => [
                            'exception' => new \PDOException(),
                        ],
                    ],
                    [
                        'level'   => LogLevel::NOTICE,
                        'message' => sprintf(
                            'Unable to find the controller for path "%s". The route is wrongly configured.',
                            'unknown/path'
                        ),
                        'context' => [
                            'exception' => new NotFoundHttpException(),
                        ],
                    ],
                    [
                        'level'   => LogLevel::WARNING,
                        'message' => sprintf(
                            'The stream or file "%s" could not be opened: '.'File not found!',
                            '/var/www/var/cache/d1c126dd-a5c2-4ae4-b3ee-386c06fb2e05.pid'
                        ),
                        'context' => [
                            'exception' => new BadRequestHttpException(),
                        ],
                    ],
                    [
                        'level'   => LogLevel::ERROR,
                        'message' => 'Noticed exception \'{exception_class}\' with message \'{file}:{line}',
                        'context' => [
                            'exception_class' => RuntimeException::class,
                            'exception'       => new RuntimeException(),
                            'file'            => __FILE__,
                            'line'            => __LINE__,
                        ],
                    ],
                    [
                        'level'   => LogLevel::ALERT,
                        'message' => 'Timeout of "{seconds} seconds" exceeded while trying to connect to host "{host}"',
                        'context' => [
                            'host'    => $generator->ipv4,
                            'seconds' => $generator->numberBetween(0, 3000),
                        ],
                    ],
                    [
                        'level'   => LogLevel::DEBUG,
                        'message' => 'Listener "{listener}" was not called for event "{event}".',
                        'context' => [
                            'listener' => ConsoleErrorSubscriber::class,
                            'event'    => ConsoleEvent::class,
                        ],
                    ],
                ]
            );
    }
}
