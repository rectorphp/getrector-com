<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject\Symfony;

/**
 * @api
 */
final class DoctrineExtension
{
    /**
     * @var string
     */
    public const NAME = 'doctrine';

    /**
     * @var string
     */
    public const ORM = 'orm';

    /**
     * @var string
     */
    public const DBAL = 'dbal';
}
