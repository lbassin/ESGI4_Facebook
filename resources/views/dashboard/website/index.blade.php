<?php
/** @var array $albums */
/** @var string $subdomain */
?>

@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

<?php /** @var \App\Http\Api\Album $album */ ?>

<div class="wrapper">
    <div class="head">
        <div class="user-pic">
            <img src="{{ $userHelper->getPicture() }}" alt="">
        </div>
        <span class="user-name">{{ $userHelper->getName() }}</span>
    </div>

    <div class="container website-home">
        <nav>
            <ul>
                <li><a href="{{ route('dashboard.website.home', ['subdomain' => $subdomain]) }}">Gestion de l'accueil</a></li>
                <li><a href="{{ route('dashboard.website.albums', ['subdomain' => $subdomain]) }}">Gestion des albums</a></li>
                <li><a href="{{ route('dashboard.website.articles', ['subdomain' => $subdomain]) }}">Gestion des articles</a></li>
                <li><a href="{{ route('dashboard.website.events', ['subdomain' => $subdomain]) }}">Gestion des evenements</a></li>
                <li><a href="{{ route('dashboard.website.reviews', ['subdomain' => $subdomain]) }}">Gestion des avis</a></li>
            </ul>
        </nav>
        <div class="menu-albums">
            <div class="menu-albums-title">
                <h2>Mes Derniers Albums</h2>
            </div>
            <?php $albums_sliced = array_slice($albums,0,6); ?>
            @foreach($albums_sliced as $album)
                <div class="album">
                    <div class="title">
                        <h2>{{ $album->getName() }}</h2>
                        <div class="gradient"></div>
                    </div>
                    <div class="image">
                        <?php ?>
                        <img src="<?php echo $album->getPreview(\App\Http\Api\Photo::SIZE_LARGE)[0]?>" alt="">
                    </div>
                </div>

            @endforeach
        </div>
    </div>

</div>


