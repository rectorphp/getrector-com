<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector\Fixture;

class SomeController
{
    public function __invoke()
    {
        $this->someMethod();
    }
}

?>
