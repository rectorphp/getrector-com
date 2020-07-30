<?php

declare(strict_types=1);

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Sentry\SentryBundle\SentryBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

return [
    FrameworkBundle::class => [
        'all' => true,
    ],
    DebugBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    TwigBundle::class => [
        'all' => true,
    ],
    WebProfilerBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    SecurityBundle::class => [
        'all' => true,
    ],
    DoctrineBundle::class => [
        'all' => true,
    ],
    DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    WebpackEncoreBundle::class => [
        'all' => true,
    ],
    SentryBundle::class => [
        'all' => true,
    ],
];
