<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject\Symfony;

final class TwigExtension
{
    /**
     * @api
     * @var string
     */
    public const NAME = 'twig';

    /**
     * @var string
     * @api
     */
    public const FORM_THEMES = 'form_themes';

    /**
     * @var string
     * @api
     */
    public const DEFAULT_PATH = 'default_path';

    /**
     * @var string
     * @api
     */
    public const DEBUG = 'debug';

    /**
     * @var string
     * @api
     */
    public const STRICT_VARIABLES = 'strict_variables';

    /**
     * @var string
     * @api
     */
    public const EXCEPTION_CONTROLLER = 'exception_controller';

    /**
     * @var string
     * @api
     */
    public const GLOBALS = 'globals';

    /**
     * @var string
     * @api
     */
    public const DATE = 'date';

    /**
     * @var string
     * @api
     */
    public const NUMBER_FORMAT = 'number_format';
}
