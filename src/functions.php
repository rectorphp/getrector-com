<?php

declare(strict_types=1);

// @see https://github.com/thephpleague/commonmark

use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;

function markdown(string $contents): Stringable
{
    $environment = new \League\CommonMark\Environment\Environment([
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]);
    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addExtension(new GithubFlavoredMarkdownExtension());

    $languages = ['php', 'yaml', 'bash'];

    $environment->addRenderer(FencedCode::class, new FencedCodeRenderer($languages));
    $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer($languages));

    $markdownConverter = new MarkdownConverter($environment);

    return $markdownConverter->convert($contents);
}
