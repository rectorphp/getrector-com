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

    public function createSection(string $slugOrUrl, string $name, bool $isNew = false): DocumentationMenuItem
    {
        if (str_starts_with($slugOrUrl, 'https://')) {
            $url = $slugOrUrl;
        } else {
            $url = $this->urlGenerator->action(DocumentationController::class, [
                'section' => $slugOrUrl,
            ]);
        }

        return new DocumentationMenuItem($url, $name, $isNew);
    }

    /**
     * @param class-string<Controller> $controllerClass
     */
    public function createInternalLink(string $controllerClass, string $label): DocumentationMenuItem
    {
        $href = $this->urlGenerator->action($controllerClass);

        return new DocumentationMenuItem($href, $label);
    }
}
