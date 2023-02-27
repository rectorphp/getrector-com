<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyControllerToLaravelControllerRector\Fixture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class SomeController extends AbstractController
{
    public function list(): Response
    {
        return $this->render('some_template.twig', [
            'key' => 'value',
        ]);
    }
}

?>
