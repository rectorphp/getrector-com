<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class FormChoices
{
    /**
     * @var array<string, int>
     */
    public const CURRENT_PHP_VERSION = [
        '5.3' => 50300,
        '5.4' => 50400,
        '5.5' => 50500,
        '5.6' => 50600,
        '7.0' => 70000,
        '7.1' => 70100,
        '7.2' => 70200,
        '7.3' => 70300,
        '7.4' => 70400,
        '8.0' => 80000,
    ];
}
