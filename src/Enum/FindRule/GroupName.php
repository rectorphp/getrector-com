<?php

declare(strict_types=1);

namespace App\Enum\FindRule;

use Rector\Set\Enum\SetGroup;

final class GroupName
{
    /**
     * @var string
     */
    public const LARAVEL = 'laravel';

    /**
     * @var string
     */
    public const PHP = SetGroup::PHP;

    /**
     * @var string
     */
    public const CORE = SetGroup::CORE;

    /**
     * @var string
     */
    public const SYMFONY = SetGroup::SYMFONY;

    /**
     * @var string
     */
    public const PHPUNIT = SetGroup::PHPUNIT;

    /**
     * @var string
     */
    public const DOCTRINE = SetGroup::DOCTRINE;

    /**
     * @var string
     */
    public const TWIG = SetGroup::TWIG;
}
