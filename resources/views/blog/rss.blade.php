<?xml version="1.0" encoding="UTF-8" ?>

<rss version="2.0"
     xmlns:content="https://purl.org/rss/1.0/modules/content/"
     xmlns:dc="https://purl.org/dc/elements/1.1/"
     xmlns:atom="https://www.w3.org/2005/Atom"
>
    <channel>
        <title>Rector Blog</title>
        <link>{{ $site_url }}/</link>
        <description>Rector Blog about Legacy Code Migrations</description>
        <pubDate>{{ date('r', now()) }}</pubDate>
        <atom:link href="{{ $site_url }}/rss.xml" rel="self" type="application/rss+xml"/>

        <lastBuildDate>{{ $most_recent_post_date_time->format('r') }}</lastBuildDate>

        {{-- https://stackoverflow.com/a/29161205/1348344 --}}
        @foreach ($posts as $post)
            @php
                /** @var \Rector\Website\Entity\Post $post */

                $post_absolute_url = $site_url . route(\Rector\Website\Enum\RouteName::POST, ['postSlug' => $post->getSlug()]);
            @endphp

            <item>
                <title><![CDATA[ {{  $post->getClearTitle() }} ]]></title>
                <link>{{ $post_absolute_url }}</link>
                <description><![CDATA[ {{ $post->getPerex() }} ]]></description>
                <content:encoded><![CDATA[ {{ $post->getHtmlContent() }} ]]></content:encoded>
                <guid isPermaLink="false">{{ $post_absolute_url }}</guid>
                <dc:creator><![CDATA[ Rector ]]></dc:creator>

                {{-- @see https://wordpress.stackexchange.com/a/229773 --}}
                <pubDate>{{ $post->getDateTime()->format('D, d M Y H:i:s +0000') }}</pubDate>
                <lastBuildDate>{{ $post->getDateTime()->format('D, d M Y H:i:s +0000') }}</lastBuildDate>

                <comments>{{ $post_absolute_url }}#disqus_thread</comments>
            </item>
        @endforeach
    </channel>
</rss>
