<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser;

final class PrinterHelper
{
    public static function printLink(string $contents, string $uuid, int $nodeId, ?int $activeNodeId): string
    {
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $uuid,
            $nodeId,
            $activeNodeId == $nodeId ? 'class="active-node"' : '',
            $contents
        );
    }
}
