<?php

declare(strict_types=1);

namespace App\Documentation;

use App\Controller\DocumentationController;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Controller;

final readonly class DocumentationMenuItemFactory
{
    public function __construct(
        private UrlGenerator $urlGenerator
    ) {
    }

    /**
     * @param non-empty-string $slugOrUrl
     */
    public function createSection(string $slugOrUrl, string $name, bool $isNew = false): DocumentationMenuItem
    {
        if (str_starts_with($slugOrUrl, 'https://') || str_starts_with($slugOrUrl, 'http://')) {
            $url = $slugOrUrl;
            $slug = null;
        } else {
            $url = $this->urlGenerator->action(DocumentationController::class, [
                'section' => $slugOrUrl,
            ]);

            $slug = $slugOrUrl;
        }

        return new DocumentationMenuItem($url, $name, $slug, $isNew);
    }

    /**
     * @param class-string<Controller> $controllerClass
     */
    public function createInternalLink(string $controllerClass, string $label): DocumentationMenuItem
    {
        $href = $this->urlGenerator->action($controllerClass);

        return new DocumentationMenuItem($href, $label, null);
    }
}
