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
                    <a href="{{ route('dashboard.website.albums.edit', ['subdomain' => $subdomain, 'id' => $album->getId()]) }}">
                        <article class="module desktop-4 tablet-6">
                            <div class="album-image"
                                 style="background: url('{{ $album->getCover() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">

                            </div>
                            <div class="album-name">
                                <span>{{ $album->getName() }}</span>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="md-modal md-effect-12">
        <div class="md-content">
            <h1>Nouvel album</h1>
            <form action="{{ route('dashboard.website.albums.create', ['subdomain' => $subdomain ]) }}">
                <div>
                    <label>
                        Nom de l'album
                        <input type="text" name="new-album-name">
                    </label>
                </div>

                <div>
                    <button id="submit-new-album">
                        Créer cet album
                    </button>
                </div>
            </form>

            <div id="messages">
                <div class="success">
                    <ul></ul>
                </div>
                <div class="errors">
                    <ul></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script>
        $('.albums-create').click(function () {
            $('.md-modal').addClass('md-show');
        });

        $('.md-close').click(function () {
            $('.md-modal').removeClass('md-show');
        });

        $('.md-content form').on('submit', function (event) {
            event.preventDefault();

            let name = $('input[name="new-album-name"]').val();

            $.post(this.action, {name: name}).done(
                function (response) {
                    if(response.error){
                        addError(response.message);
                        return;
                    }

                    addSuccess('Album created');
                    setTimeout(function(){
                        window.location.href = response.url;
                    }, 250);
                }
            ).fail(
                function (response) {
                    addError(response.responseJSON.message);
                }
            );
        });

        function addSuccess(message) {
            let wrapper = $('#messages .success ul');

            let success = $('<li>').text(message);
            wrapper.append(success);
        }

        function addError(message) {
            let wrapper = $('#messages .errors ul');

            let error = $('<li>').text(message);
            wrapper.append(error);
        }
    </script>

@endsection