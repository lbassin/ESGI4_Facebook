<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <?php // TODO : Dynamic build (Routeur action return json) ?>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
</head>
<body>
<nav>
    <ul>
        @if(isset($menu[\App\Model\Menu::ACCUEIL]) && $menu[\App\Model\Menu::ACCUEIL])
            <li><a href="{{ route('website.home', ['subdomain' => $subdomain]) }}">Accueil</a></li>
        @endif
        @if(isset($menu[\App\Model\Menu::ALBUMS]) && $menu[\App\Model\Menu::ALBUMS])
            <li><a href="{{ route('website.albums', ['subdomain' => $subdomain]) }}">Albums</a></li>
        @endif
        @if(isset($menu[\App\Model\Menu::ARTICLES]) && $menu[\App\Model\Menu::ARTICLES])
            <li><a href="{{ route('website.articles', ['subdomain' => $subdomain]) }}">Articles</a></li>
        @endif
        @if(isset($menu[\App\Model\Menu::EVENTS]) && $menu[\App\Model\Menu::EVENTS])
            <li><a href="{{ route('website.events', ['subdomain' => $subdomain]) }}">Evenements</a></li>
        @endif
        @if(isset($menu[\App\Model\Menu::REVIEWS]) && $menu[\App\Model\Menu::REVIEWS])
            <li><a href="{{ route('website.reviews', ['subdomain' => $subdomain]) }}">Avis</a></li>
        @endif
    </ul>
</nav>
@yield('content')
</body>
</html>
