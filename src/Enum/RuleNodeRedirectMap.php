<?php

declare(strict_types=1);

namespace Rector\Website\Enum;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use Rector\Contract\Rector\RectorInterface;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;

/**
 * Some rules are bound to higher level nodes, to have more context,
 * but the search should them for a node they actually change.
 *
 * E.g. the class const type rule hooks to class, but actually should be visible for class const
 */
final class RuleNodeRedirectMap
{
    /**
     * @var array<class-string<RectorInterface>, array<class-string<Node>>>
     */
    public const MAP = [
        AddTypeToConstRector::class => [ClassConst::class],
    ];
}
