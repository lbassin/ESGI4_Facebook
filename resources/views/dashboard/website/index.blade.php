<?php
/** @var array $albums */
/** @var string $subdomain */
?>

<?php /** @var \Facebook\GraphNodes\GraphNode $album */ ?>
@foreach($albums as $album)
    <h2>{{ $album->getField('name') }}</h2>
@endforeach

<ul>
    <li><a href="{{ route('dashboard.website.home', ['subdomain' => $subdomain]) }}">Gestion de l'accueil</a></li>
    <li><a href="{{ route('dashboard.website.albums', ['subdomain' => $subdomain]) }}">Gestion des albums</a></li>
    <li><a href="{{ route('dashboard.website.articles', ['subdomain' => $subdomain]) }}">Gestion des articles</a></li>
    <li><a href="{{ route('dashboard.website.events', ['subdomain' => $subdomain]) }}">Gestion des evenements</a></li>
    <li><a href="{{ route('dashboard.website.reviews', ['subdomain' => $subdomain]) }}">Gestion des avis</a></li>
</ul>