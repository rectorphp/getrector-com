<?php

declare(strict_types=1);

namespace App\RuleFilter\Enum;

final readonly class MagicSearch
{
    /**
     * @var string
     */
    public const SYMFONY_RULES = 'symfony rules';

    /**
     * @var string
     */
    public const DOCTRINE_RULES = 'doctrine rules';

    /**
     * @var string
     */
    public const PHPUNIT_RULES = 'phpunit rules';

    /**
     * @var string
     */
    public const DOWNGRADE_RULES = 'downgrade rules';
}
