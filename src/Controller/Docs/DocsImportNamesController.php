<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Docs;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocsImportNamesController extends AbstractController
{
    #[Route(path: 'docs/import_names', name: RouteName::DOCS_IMPORT_NAMES)]
    public function __invoke(): Response
    {
        return $this->render('docs/import_names.twig');
    }
}
