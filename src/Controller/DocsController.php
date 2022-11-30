<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocsController extends AbstractController
{
    #[Route(path: 'docs', name: RouteName::DOCS)]
    public function __invoke(): Response
    {
        // loda suffix
        // load from file
        // parse markdown to HTML :)
        // render menu ;)

        return $this->render('docs/introduction.twig');
    }
}
