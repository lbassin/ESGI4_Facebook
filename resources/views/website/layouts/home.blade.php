<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <?php // TODO : Dynamic build (Routeur action return json) ?>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
</head>
<body>
<nav>
    <ul>
        <li><a href="{{ route('website.home', ['subdomain' => $subdomain]) }}">Accueil</a></li>
        <li><a href="{{ route('website.albums', ['subdomain' => $subdomain]) }}">Albums</a></li>
        <li><a href="{{ route('website.articles', ['subdomain' => $subdomain]) }}">Articles</a></li>
        <li><a href="{{ route('website.events', ['subdomain' => $subdomain]) }}">Evenements</a></li>
        <li><a href="{{ route('website.reviews', ['subdomain' => $subdomain]) }}">Avis</a></li>
    </ul>
</nav>
@yield('content')
</body>
</html>
