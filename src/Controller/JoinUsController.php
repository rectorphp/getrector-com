<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class JoinUsController extends AbstractController
{
    /**
     * @Route(path="join-us", name="join_us")
     */
    public function __invoke(): Response
    {
        return $this->render('homepage/join_us.twig');
    }
}
