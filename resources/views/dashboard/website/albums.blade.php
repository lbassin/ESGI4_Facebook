@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div class="wrapper">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userpic }}" alt="">
            </div>
            <span class="user-name">{{ $name }}</span>
        </div>

        <div class="wrapper-albums">
            <div class="albums-nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="albums-back">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span>Retour à l'accueil</span>
                </span>
                </a>
                <span class="albums-title">Gérer les albums</span>
                <span class="albums-create">
                    <span>Créer un album</span>
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="wrapper-pictures">
            <?php /** @var \App\Http\Api\Album $album */ ?>
            @foreach($albums as $album)
                <?php dump($album->getPreview(\App\Http\Api\Photo::SIZE_SMALL)); ?>
            @endforeach
        </div>
    </div>

    <script>
        $('.albums-create').on('click', function () {
            let name = prompt('Nom de l\'album ?');

            $.post(window.URLs.albums.create, { name: name }, function(){
                alert('Created');
            });
        });
    </script>

@endsection