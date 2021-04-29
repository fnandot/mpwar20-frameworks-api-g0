<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Define what rule sets will be applied
    $parameters->set(
        Option::SETS,
        [
            SetList::DEAD_CODE,
            SetList::PHP_80,
        ]
    );

    // get services (needed for register a single rule)
    // $services = $containerConfigurator->services();

    // register a single rule
    // $services->set(TypedPropertyRector::class);
    // paths to refactor; solid alternative to CLI arguments
    //$parameters->set(Option::PATHS, [__DIR__.'/src', __DIR__.'/tests']);
    //
    // Rector is static reflection to load code without running it - see https://phpstan.org/blog/zero-config-analysis-with-static-reflection
    $parameters->set(
        Option::BOOTSTRAP_FILES,
        [
        // autoload specific file
        __DIR__.'/vendor/autoload.php',
        ]
    );

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [__DIR__.'/src']);
};
