@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div class="wrapper">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userHelper->getPicture() }}" alt="">
            </div>
            <span class="user-name">{{ $userHelper->getName() }}</span>
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
            <div class="grid">
                <?php /** @var \App\Http\Api\Album $album */ ?>
                @foreach($albums as $album)
                <article class="module desktop-4 tablet-6" style="background: url('{{ $album->getCover() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">
                    <figure class="front">
                        <div class="caption">
                            <h2>{{ $album->getName() }}</h2>
                            <p>{{ $album->getDescription() }}</p>
                        </div>
                    </figure>
                </article>
                @endforeach
            </div>
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