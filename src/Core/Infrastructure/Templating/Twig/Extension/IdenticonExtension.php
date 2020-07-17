<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Infrastructure\Templating\Twig\Extension;

use Identicon\Generator\SvgGenerator;
use Identicon\Identicon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class IdenticonExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('identicon', [$this, 'generateIdenticon']),
        ];
    }

    public function generateIdenticon(string $data): string
    {
        return (new Identicon(new SvgGenerator()))->getImageDataUri('$data');
    }
}
