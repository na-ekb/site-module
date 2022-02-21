<head>
    <title>{{ $page->title ?? '' }}</title>
    <meta name="description" content="{{ $page->pageMeta->description ?? '' }}">
    <meta name="keywords" content="АН, Анонимные Наркоманы, Екатеринбург, Свердловская область">
    <link rel="icon" type="image/png" href="{{ asset('img/og-logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <link type="text/plain" rel="author" href="{{ asset('humans.txt') }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $page->title ?? '' }}">
    <meta property="og:description" content="{{ $page->pageMeta->small_desc ?? '' }}">
    <meta property="og:image" content="{{ asset('img/og-logo.png') }}">
    <meta property="og:image:width" content="90">
    <meta property="og:image:height" content="90">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:text:title" content="{{ $page->title ?? '' }}">
    <meta name="twitter:image" content="{{ asset('img/og-logo.png') }}">
    <meta name="twitter:text:description" content="{{ $page->pageMeta->small_desc ?? '' }}">

    <meta property="vk:image" content="{{ asset('img/vk-logo.png') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/favicon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#495c9c">
    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#495c9c">
    <meta name="msapplication-config" content="/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#495c9c">

    {!! $meta->meta_tags ?? '' !!}
</head>