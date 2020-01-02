<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DemoController extends AbstractController
{
    /**
     * @Route(path="demo", name="demo")
     */
    public function __invoke(): Response
    {
        return $this->render('homepage/demo.twig');
    }
}
