<?php

declare(strict_types=1);

// @see https://github.com/thephpleague/commonmark

use App\Enum\FlashType;
use App\Markdown\GithubMarkdownConverter;
use Illuminate\Http\RedirectResponse;

function latestPhp(): string
{
    return '8.3';
}

function slugify(string $value): string
{
    return str($value)->slug()
        ->value();
}

function markdown(string $contents): Stringable
{
    // @see https://commonmark.thephpleague.com/1.5/extensions/heading-permalinks/
    $markdownConverter = new GithubMarkdownConverter([
        'allow_unsafe_links' => false,
        'heading_permalink' => [
            'html_class' => 'anchor-link', // CSS class for the anchor
            'symbol' => '#',               // Symbol for the link
            'id_prefix' => 'content',     // Prefix for the ID
            'insert' => 'after',          // Where to insert the link: 'before', 'after', or 'none'
        ],
    ]);

    return $markdownConverter->convert($contents);
}

function redirect_with_error(string $controller, string $errorMessage): RedirectResponse
{
    session()->flash(FlashType::ERROR, $errorMessage);

    return redirect()->action($controller);
}
