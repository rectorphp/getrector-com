<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject\Symfony;

final class SecurityExtension
{
    /**
     * @api
     * @var string
     */
    public const NAME = 'security';

    /**
     * @api
     * @var string
     */
    public const ACCESS_CONTROL = 'access_control';

    /**
     * @api
     * @var string
     */
    public const PROVIDERS = 'providers';

    /**
     * @api
     * @var string
     */
    public const FIREWALLS = 'firewalls';

    /**
     * @api
     * @var string
     */
    public const ENCODERS = 'encoders';
}
