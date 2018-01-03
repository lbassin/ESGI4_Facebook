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
                <?php /** @var \Facebook\GraphNodes\GraphNode $album */ ?>
                @foreach($albums as $album)
                <article class="module desktop-4 tablet-6">
                    <div class="album-image" style="background: url('{{ $album->getPreview(\App\Http\Api\Photo::SIZE_LARGE)[0] }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">
                        
                    </div>
                    <div class="album-name">
                        <span>{{ $album->getName() }}</span>
                    </div>
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