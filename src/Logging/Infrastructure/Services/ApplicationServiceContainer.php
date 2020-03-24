<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Services;

use LaSalle\GroupZero\Logging\Application\ApplicationService;

final class ApplicationServiceContainer
{
    /** @var ApplicationService[] */
    private $services;

    /**
     * @param ApplicationService[] $services
     */
    public function __construct(array $services = [])
    {
        $this->services = [];

        foreach ($services as $service) {
            $this->add($service);
        }
    }

    public function add($service): void
    {
        $this->services[get_class($service)] = $service;
    }

    /**
     * @return ApplicationService[]
     */
    public function all(): array
    {
        return $this->services;
    }
}
