<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Documentation\DocumentationMenuFactory;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocsController extends AbstractController
{
    public function __construct(
        private readonly DocumentationMenuFactory $documentationMenuFactory
    ) {
    }

    #[Route(path: 'docs', name: RouteName::DOCS)]
    public function __invoke(): Response
    {
        return $this->render('docs/introduction.twig', [
            'documentations_sections' => $this->documentationMenuFactory->create(),
        ]);
    }
}
