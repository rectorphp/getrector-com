<?php

declare(strict_types=1);

namespace Rector\Website\Blog\DataProvider;

use Symplify\Statie\Generator\Generator;
use Symplify\Statie\Renderable\File\PostFile;

final class PostsProvider
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return PostFile[]
     */
    public function provide(): array
    {
        return $this->generator->run()['posts'] ?? [];
    }
}
