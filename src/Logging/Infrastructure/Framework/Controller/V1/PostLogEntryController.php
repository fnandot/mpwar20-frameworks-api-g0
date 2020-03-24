<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Controller\V1;

use DateTimeImmutable;
use DateTimeInterface;
use LaSalle\GroupZero\Logging\Application\CreateLogEntry;
use LaSalle\GroupZero\Logging\Application\CreateLogEntryRequest;
use LaSalle\GroupZero\Logging\Domain\Model\Exception\InvalidLogLevelException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final class PostLogEntryController extends AbstractController
{
    /**
     * @Route("/log-entries-without-serializer", methods={"POST"}, requirements={"version"="v1"})
     */
    public function __invoke(Request $request, CreateLogEntry $createLogEntry): Response
    {
        $data = $request->request->all();

        try {
            $createLogEntryRequest = new CreateLogEntryRequest(
                (string) Uuid::uuid4(),
                $data['environment'],
                $data['level'],
                $data['message'],
                DateTimeImmutable::createFromFormat(
                    DateTimeInterface::W3C,
                    $data['occurred_on']
                )
            );
        } catch (Throwable $t) {
            throw new BadRequestHttpException('Bad request', $t);
        }

        $logEntry = $createLogEntry($createLogEntryRequest);

        return $this->json($logEntry, Response::HTTP_OK, [], ['groups' => 'api-v1']);
    }

    /**
     * @Route("/log-entries", methods={"POST"}, requirements={"version"="v1"})
     */
    public function withSerializer(
        Request $request,
        CreateLogEntry $createLogEntry,
        SerializerInterface $serializer
    ): Response {
        $data = $request->getContent();
        try {
            /** @var CreateLogEntryRequest $createLogEntryRequest */
            $createLogEntryRequest = $serializer->deserialize(
                $data,
                CreateLogEntryRequest::class,
                'json',
                ['groups' => 'api-v1']
            );

            $logEntry = $createLogEntry($createLogEntryRequest);
        } catch (InvalidLogLevelException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        } catch (Throwable $t) {
            throw new BadRequestHttpException('Bad request', $t);
        }

        return $this->json($logEntry, Response::HTTP_OK, [], ['groups' => 'api-v1']);
    }
}
