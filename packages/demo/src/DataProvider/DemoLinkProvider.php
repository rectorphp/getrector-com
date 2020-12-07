<?php

declare(strict_types=1);

namespace Rector\Website\Demo\DataProvider;

use Rector\Website\Demo\ValueObject\DemoLink;

final class DemoLinkProvider
{
    /**
     * @return DemoLink[]
     */
    public function provide(): array
    {
        $demoLinks = [];
        $demoLinks[] = new DemoLink('PHP 7.4 Typed Properties', '19ac6368-a647-43eb-a762-d16abe61dfff');
        $demoLinks[] = new DemoLink('Early Return', '950be432-0e91-4bbf-837e-080f0329d9d4');
        $demoLinks[] = new DemoLink('Null Coalescing', '81d6c6c4-a8e1-4eee-a1fb-24599aee4e5e');
        return $demoLinks;
    }
}
