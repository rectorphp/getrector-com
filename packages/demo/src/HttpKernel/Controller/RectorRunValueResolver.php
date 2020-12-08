<?php

declare(strict_types=1);

namespace Rector\Website\Demo\HttpKernel\Controller;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Repository\RectorRunRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Uuid;

final class RectorRunValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(private RectorRunRepository $rectorRunRepository)
    {
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
     * @return RectorRun[]
     */
    public function resolve(Request $request, ArgumentMetadata $argumentMetadata): iterable
    {
        $argumentValue = $request->get($argumentMetadata->getName());

        $uuid = Uuid::fromString($argumentValue);
        return [$this->rectorRunRepository->get($uuid)];
    }
}
