<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Rector\Website\Form\RectorRunFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DemoController extends AbstractController
{
    /**
     * @Route(path="demo", name="demo")
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(RectorRunFormType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $this->createForm(RectorRunFormType::class);
            $form->handleRequest($request);

            dump($form->getData());
            die;
        }

        dump(123);

        return $this->render('homepage/demo.twig', [
            'form' => $form->createView(),
        ]);
    }
}
