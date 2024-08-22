<?php

declare(strict_types=1);

namespace App\Enum;

final class FindRuleQuery
{
    /**
     * @var string[]
     */
    public const EXAMPLES = [
        'attributes',
        'add constant type',
        'remove tag',
        'add return type strict',
        'symfony rules',
    ];
}
