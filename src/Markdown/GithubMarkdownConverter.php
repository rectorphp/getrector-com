<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Same as \League\CommonMark\GithubFlavoredMarkdownConverter
 * just with HeadingPermalinkExtension
 */
final class GithubMarkdownConverter extends MarkdownConverter
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        // @see https://commonmark.thephpleague.com/1.5/extensions/heading-permalinks/
        $environment->addExtension(new HeadingPermalinkExtension());

        parent::__construct($environment);
    }
}
