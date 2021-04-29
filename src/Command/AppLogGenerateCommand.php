<?php

namespace LaSalle\GroupZero\Command;

use LogicException;
use PDOException;
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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class AppLogGenerateCommand extends Command implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    protected static $defaultName = 'app:log:generate';

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

    public static function getSubscribedServices(): array
    {
        return [
            'request' => LoggerInterface::class,
            'app'     => LoggerInterface::class,
            'router'  => LoggerInterface::class,
            'php'     => LoggerInterface::class,
            'event'   => LoggerInterface::class,
        ];
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
        dump($this->container);

        $delay = (int)$input->getOption('delay');

        for ($i = (int)$input->getOption('iterations'); $i > 0; --$i) {
            $logData = $this->randomLog();

            $this->getLogger($logData['channel'])->log($logData['level'], $logData['message'], $logData['context']);

            usleep($delay);
        }

        return 0;
    }

    private function randomLog(): array
    {
        $logs = [
            [
                'level'   => LogLevel::NOTICE,
                'channel' => 'app',
                'message' => 'User identified by "{user_id}" was not created due "{reason}"!',
                'context' => [
                    'user_id'   => $this->randomUserId(),
                    'reason'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                    'exception' => new LogicException(),
                ],
            ],
            [
                'level'   => LogLevel::INFO,
                'channel' => 'app',
                'message' => 'User identified by "{user_id}" could not be found"!',
                'context' => [
                    'user_id'   => $this->randomUserId(),
                    'exception' => new NotFoundHttpException(),
                ],
            ],
            [
                'level'   => LogLevel::INFO,
                'channel' => 'app',
                'message' => 'Added user role "{role}" to user identified by "{user_id}"',
                'context' => [
                    'user_id' => $this->randomUserId(),
                    'role'    => 'admin',
                ],
            ],
            [
                'level'   => LogLevel::CRITICAL,
                'channel' => 'request',
                'message' => sprintf(
                    'Uncaught PHP Exception %s: "%s" at %s line %s',
                    'PDOException',
                    'Could not connect to database.',
                    __FILE__,
                    __LINE__
                ),
                'context' => [
                    'exception' => new PDOException(),
                ],
            ],
            [
                'level'   => LogLevel::NOTICE,
                'channel' => 'router',
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
                'channel' => 'php',
                'message' => sprintf(
                    'The stream or file "%s" could not be opened: ' . 'File not found!',
                    '/var/www/var/cache/d1c126dd-a5c2-4ae4-b3ee-386c06fb2e05.pid'
                ),
                'context' => [
                    'exception' => new BadRequestHttpException(),
                ],
            ],
            [
                'level'   => LogLevel::ERROR,
                'channel' => 'request',
                'message' => 'Noticed exception \'{exception_class}\' with message \'{file}:{line}',
                'context' => [
                    'exception_class' => RuntimeException::class,
                    'exception'       => new RuntimeException(),
                    'file'            => __FILE__,
                    'line'            => __LINE__,
                ],
            ],
            [
                'level'   => LogLevel::CRITICAL,
                'channel' => 'app',
                'message' => 'Token {id} is expired and cannot be used (iat: {iat})',
                'context' => [
                    'id'        => (string)Uuid::uuid4(),
                    'iat'       => time(),
                    'exception' => new AccessDeniedHttpException(),
                ],
            ],
            [
                'level'   => LogLevel::ALERT,
                'channel' => 'request',
                'message' => 'Timeout of "{seconds} seconds" exceeded while trying to connect to host "{host}"',
                'context' => [
                    'host'    => '10.17.213.77',
                    'seconds' => random_int(0, 3000),
                ],
            ],
            [
                'level'   => LogLevel::DEBUG,
                'channel' => 'event',
                'message' => 'Listener "{listener}" was not called for event "{event}".',
                'context' => [
                    'listener' => ConsoleErrorSubscriber::class,
                    'event'    => ConsoleEvent::class,
                ],
            ],
            [
                'level'   => LogLevel::EMERGENCY,
                'channel' => 'app',
                'message' => 'Could not connect to AWS SQS.',
                'context' => [
                    'listener' => ConsoleErrorSubscriber::class,
                    'event'    => ConsoleEvent::class,
                ],
            ],
            [
                'level'   => LogLevel::ALERT,
                'channel' => 'app',
                'message' => 'Could not connect to AWS SQS.',
                'context' => [
                    'listener' => ConsoleErrorSubscriber::class,
                    'event'    => ConsoleEvent::class,
                ],
            ],
            [
                'level'   => LogLevel::INFO,
                'channel' => 'request',
                'message' => 'Matched route "{route}".',
                'context' => [
                    'route'            => 'patch_user',
                    'route_parameters' => [],
                    'request_uri'      => 'https://symfony.com/doc/current/routing.html',
                    'method'           => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'][random_int(0, 4)],
                ],
            ],
        ];

        return $logs[array_rand($logs)];
    }

    private function randomUserId(): string
    {
        $userIds = [
            'b816b2b7-5fb7-48bc-b563-91ac9f6d1d71',
            'b816b2b7-5fb7-48bc-b563-91ac9f6d1d71',
            'b816b2b7-5fb7-48bc-b563-91ac9f6d1d71',
            '22cbdcc5-da48-45b6-8289-28c7c5bb3db8',
            '7e8f339f-4bf6-4223-abc2-d28bde8c5a11',
            '7e8f339f-4bf6-4223-abc2-d28bde8c5a11',
            'bec07183-71f3-4a12-b875-2b699fdfe9ad',
            '46ce6f16-a2be-4112-bbdd-88575e9293a5',
            'cd0aed4a-4669-4a51-af7a-2e6cfa34f660',
            '9349fc89-252a-4838-9682-d72cf0d0858b',
        ];

        return $userIds[array_rand($userIds)];
    }

    private function getLogger(string $channel): LoggerInterface
    {
        return $this->container->get($channel);
    }
}
