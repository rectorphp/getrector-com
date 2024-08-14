<?php

declare(strict_types=1);

namespace App\Documentation;

use App\Controller\DocumentationController;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Controller;

final class DocumentationMenuItemFactory
{
    public function __construct(
        private UrlGenerator $urlGenerator
    ) {
    }

    public function createSection(string $slug, string $name, bool $isNew = false): DocumentationMenuItem
    {
        return new DocumentationMenuItem(
            $this->urlGenerator->action(DocumentationController::class, [
                'section' => $slug,
            ]),
            $name,
            $isNew
        );

    }

    /**
     * @param class-string<Controller>|list<class-string<Controller>|string> $actionController
     */
    public function createInternalLink(
        string|array $actionController,
        string $label,
        mixed $actionParameters = null,
        bool $isNew = false,
    ): DocumentationMenuItem {
        return new DocumentationMenuItem(
            $this->urlGenerator->action($actionController, $actionParameters),
            $label,
            $isNew,
        );
    }
}
