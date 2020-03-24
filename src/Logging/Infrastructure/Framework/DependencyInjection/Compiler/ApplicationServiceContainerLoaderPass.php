<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\DependencyInjection\Compiler;

use LaSalle\GroupZero\Logging\Application\ApplicationService;
use LaSalle\GroupZero\Logging\Infrastructure\Services\ApplicationServiceContainer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class ApplicationServiceContainerLoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ApplicationServiceContainer::class)) {
            return;
        }

        $taggedServices = $container
            ->findTaggedServiceIds('group_zero.application_service');

        $definitions = [];

        foreach ($taggedServices as $serviceId => $tags) {
            $definitions[] = new Reference($serviceId);
        }

        $container
            ->getDefinition(ApplicationServiceContainer::class)
            ->addArgument($definitions);
    }
}
