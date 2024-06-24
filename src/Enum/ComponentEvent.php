<?php

declare(strict_types=1);

namespace App\Enum;

final class ComponentEvent
{
    /**
     * @var string
     */
    public const SELECT_NODE = 'select_node';

    /**
     * @var string
     */
    public const NODE_SELECTED = 'node_selected';

    /**
     * @var string
     */
    public const RULES_FILTERED = 'rules_filtered';
}
