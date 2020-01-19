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
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (! is_a($argument->getType(), UuidInterface::class, true)) {
            return false;
        }

        $argumentValue = $request->get($argument->getName());

        return $argumentValue !== null;
    }

    /**
     * @return UuidInterface|null
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Iterator
    {
        $argumentValue = $request->get($argument->getName());

        yield Uuid::fromString($argumentValue);
    }
}
