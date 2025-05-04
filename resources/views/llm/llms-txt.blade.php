@php
    /** @var \App\Entity\Post[] $posts */
    /** @var array<string, \App\Documentation\DocumentationMenuItem[]> $documentationMenuItemsBySection */
@endphp


# Rector

We help successful and growing companies to get the most out of the code they already have.

Reduce maintenance cost, make feature delivery cheaper
and turn legacy code into sustainable code.


## How does Rector Improve your Business?

Rector is a PHP tool that you can run on any PHP project to get an instant upgrade or automated refactoring.

It helps you with:

* PHP and framework upgrades,
* in-house framework migrations,
* improving your code quality to deliver features faster than competition

In the hands of an expert, Rector massively reduces your work-time.
Where project upgrade PHP 8.0 to 8.4 would take 3 months, Rector is done in 3 days.

You can learn it yourself from documentation, or to save time and start upgrading today, hire our upgrade team.

=========================================

# Documentation

@foreach ($documentationMenuItemsBySection as $section => $documentationMenuItems)
    @foreach ($documentationMenuItems as $documentationMenuItem)
        @continue ($documentationMenuItem->getSlug() === null)

## {{ $section }}: {{ $documentationMenuItem->getLabel() }}

{!! $documentationMenuItem->getMarkdownContents() !!}

-----------------------------------------

    @endforeach
@endforeach

=========================================

# Blog posts

@foreach ($posts as $post)
## {{ $post->getTitle() }}

Perex: {!! $post->getPerex() !!}

{!! $post->getContents() !!}

-----------------------------------------
@endforeach
