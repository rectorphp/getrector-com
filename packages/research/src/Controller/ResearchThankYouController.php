<?php

declare(strict_types=1);

namespace Rector\Website\Research\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ResearchThankYouController extends AbstractController
{
    /**
     * @Route(path="research", name="research", methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('research/thank-you.twig');
    }
}
