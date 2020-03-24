<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1\ValueResolver;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use LaSalle\GroupZero\Logging\Application\CreateLogEntryRequest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

final class CreateLogEntryRequestValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return CreateLogEntryRequest::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $data = $request->request->all();

        try {
            yield new CreateLogEntryRequest(
                $this->resolveIdentifier($data),
                $data['environment'],
                $data['level']['value'],
                $data['message'],
                DateTimeImmutable::createFromFormat(
                    DateTimeInterface::W3C,
                    $data['occurred-on']
                )
            );
        } catch (Throwable $t) {
            throw new BadRequestHttpException('Bad request', $t);
        }
    }

    private function resolveIdentifier($data): string
    {
        if (array_key_exists('id', $data)) {
            return (string) $data['id'];
        }

        return (string) Uuid::uuid4();
    }
}
