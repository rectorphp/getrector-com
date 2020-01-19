<?php

declare(strict_types=1);

namespace Rector\Website\HttpKernel\Controller;

use Iterator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @see https://symfony.com/doc/current/controller/argument_value_resolver.html#adding-a-custom-value-resolver
 */
final class UuidValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argumentMetadata): bool
    {
        if ($argumentMetadata->getType() === null) {
            return false;
        }

        if (! is_a($argumentMetadata->getType(), UuidInterface::class, true)) {
            return false;
        }

        $argumentValue = $request->get($argumentMetadata->getName());

        return $argumentValue !== null;
    }

    public function resolve(Request $request, ArgumentMetadata $argumentMetadata): Iterator
    {
        $argumentValue = $request->get($argumentMetadata->getName());

        yield Uuid::fromString($argumentValue);
    }
}
