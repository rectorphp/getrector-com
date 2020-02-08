<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Rector\Website\DemoRunner;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Form\DemoFormType;
use Rector\Website\FormDataFactory\DemoFormDataFactory;
use Rector\Website\Repository\RectorRunRepository;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ResearchController extends AbstractController
{
    /**
     * @Route(path="research", name="research", methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->render('research/research.twig');
    }
}
