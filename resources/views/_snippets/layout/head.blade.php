<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

@isset ($metaDescription)
    <meta name="description" content="{{ $metaDescription }}" />
    <meta property="og:description" content="{{ $metaDescription }}" >
@else
    <meta name="description" content="{{ \App\Enum\Design::MAIN_TITLE }}" />
@endisset
    <meta name="keywords" content="php, rector, instant upgrades, instant refactoring, upgrade symfony, upgrade php, upgrade cakephp, upgrade legacy, php migration, laravel shift alternative, php shift" />

@isset ($metaTitle)
    <title>{{ $metaTitle }}</title>
    <meta property="og:title" content="{{ $metaTitle }}">
@else
    @php
        $full_title = (isset($page_title) ? $page_title . ' | ' : '') . \App\Enum\Design::MAIN_TITLE;
    @endphp
    <title>{{ $full_title }}</title>
    <meta property="og:title" content="{{ $full_title }}">
@endisset

<link rel="alternate" type="application/rss+xml" title="Rector Blog RSS" href="/rss.xml">

<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">

@hasSection('social_tags')
    @yield('social_tags')
@else
    <!-- default social tags -->
    <meta property="twitter:card" content="summary">

    <meta property="og:title" content="{{ $full_title }}">
    <meta property="twitter:title" content="{{ $full_title }}">

    <meta property="og:image" content="{{ \App\Enum\Design::SOCIAL_RECTOR_LOGO }}">
    <meta name="twitter:image" content="{{ \App\Enum\Design::SOCIAL_RECTOR_LOGO }}">
    <meta property="og:type" content="website">
@endif


{{-- socials sharing - Twitter at least --}}
<meta name="twitter:site" content="@rectorphp">
<meta name="twitter:creator" content="@rectorphp"/>
