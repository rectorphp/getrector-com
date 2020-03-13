<?php

declare(strict_types=1);

namespace Rector\Website\Demo\HttpKernel\Controller;

use Iterator;
use Ramsey\Uuid\Uuid;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RectorRunValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var RectorRunRepository
     */
    private $rectorRunRepository;

    public function __construct(RectorRunRepository $rectorRunRepository)
    {
        $this->rectorRunRepository = $rectorRunRepository;
    }

    public function supports(Request $request, ArgumentMetadata $argumentMetadata): bool
    {
        if ($argumentMetadata->getType() !== RectorRun::class) {
            return false;
        }

        $argumentValue = $request->get($argumentMetadata->getName());

        return $argumentValue !== null;
    }

    /**
     * @return RectorRun
     */
    public function resolve(Request $request, ArgumentMetadata $argumentMetadata): Iterator
    {
        $argumentValue = $request->get($argumentMetadata->getName());

        $uuid = Uuid::fromString($argumentValue);

        yield $this->rectorRunRepository->get($uuid);
    }
}
