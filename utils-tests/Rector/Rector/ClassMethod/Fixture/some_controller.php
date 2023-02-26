<?php

declare(strict_types=1);

namespace TomasVotruba\Utils\Tests\Rector\Rector\ClassMethod\Fixture;

use Symfony\Component\Routing\Annotation\Route;

class SomeController
{
    #[Route(path: '/some', name: 'some')]
    public function __invoke()
    {
        $this->someMethod();
    }
}
