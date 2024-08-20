<?php

declare(strict_types=1);

namespace App\Enum;

final class StepBreakpoint
{
    /**
     * @var int
     */
    public const TYPE_DECLARATION_LEVEL = 5;

    /**
     * @var int
     */
    public const PHP_70 = 56;

    /**
     * @var int
     */
    public const DEAD_CODE_LEVEL = 60;

    /**
     * @var int
     */
    public const CODE_QUALITY_LEVEL = 110;

    /**
     * @var int
     */
    public const IMPORT_NAMES = 161;

    /**
     * @var int
     */
    public const PHP_80 = 164;

    /**
     * @var int
     */
    public const PREPARED_SETS = 169;
}
