<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Twig;

use ArrayLookup\AtLeast;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RoutingTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): iterable
    {
        $isCurrentRoute = new TwigFunction(
            'is_current_route',
            fn (string $desiredRouteName): bool => $this->isCurrentRoute($desiredRouteName)
        );

        $isCurrentRoutes = new TwigFunction('is_current_routes', function (array $desiredRouteNames): bool {
            $filter = fn (string $desiredRouteName): bool => $this->isCurrentRoute($desiredRouteName);
            return AtLeast::once($desiredRouteNames, $filter);
        });

        return [$isCurrentRoute, $isCurrentRoutes];
    }

    private function isCurrentRoute(string $desiredRouteName): bool
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (! $currentRequest instanceof Request) {
            throw new ShouldNotHappenException();
        }

        return $this->resolveCurrentRoute($currentRequest) === $desiredRouteName;
    }

    private function resolveCurrentRoute(Request $request): string
    {
        $currentRouteName = $request->get('_route');

        return ltrim((string) $currentRouteName, '/');
    }
}
