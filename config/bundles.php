<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;

return [
    FrameworkBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    WebpackEncoreBundle::class => ['all' => true],
    DebugBundle::class => ['dev' => true, 'test' => true],
];
