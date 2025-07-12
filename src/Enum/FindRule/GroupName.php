<?php

declare(strict_types=1);

namespace App\Enum\FindRule;

use Rector\Set\Enum\SetGroup;

final class GroupName
{
    public const string PHP = SetGroup::PHP;

    public const string CORE = SetGroup::CORE;

    public const string SYMFONY = SetGroup::SYMFONY;

    public const string PHPUNIT = SetGroup::PHPUNIT;

    public const string DOCTRINE = SetGroup::DOCTRINE;

    public const string TWIG = SetGroup::TWIG;

    public const string LARAVEL = SetGroup::LARAVEL;
}
