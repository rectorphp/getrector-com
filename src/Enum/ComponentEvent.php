<?php

declare(strict_types=1);

namespace App\Enum;

final class ComponentEvent
{
    public const string SELECT_NODE = 'select_node';

    public const string NODE_SELECTED = 'node_selected';

    public const string RULES_FILTERED = 'rules_filtered';
}
