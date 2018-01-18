@extends('layouts.app')

@section('title', ucfirst($subdomain) . ' - Albums')

@section('header_scripts')
@endsection

@section('content')
    <div class="wrapper">
        @include('dashboard.website.partial.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Gérer les albums</span>
                <span class="nav-create">
                    <span>Créer un album</span>
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="list-content">
            <div class="grid">
                <?php /** @var \App\Http\Api\Album $album */ ?>
                @foreach($albums as $album)
                    <a href="{{ route('dashboard.website.albums.edit', ['subdomain' => $subdomain, 'id' => $album->getId()]) }}">
                        <article class="module desktop-4 tablet-6">
                            <div class="element-image zoom"
                                 style="background: url('{{ $album->getCover() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">

                            </div>
                            <div class="element-name">
                                <span>{{ $album->getName() }}</span>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div id="create-album-modal" class="md-modal md-effect-12">
        <div class="md-content">
            <h1>Nouvel album</h1>
            <form action="{{ route('dashboard.website.albums.create', ['subdomain' => $subdomain ]) }}" class="m-form">
                <div class="input input-text">
                    <input type="text" name="new-album-name" required>
                    <label>Nom de l'album</label>
                </div>

                <div>
                    <button id="submit-new-album">
                        Créer cet album
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    @include('dashboard.website.partial.loader')

    <script>
        $("#nav a").on('click', function() {
            showLoader('loader');
        });
        $(".grid a").on('click', function() {
            showLoader('loader');
        });

        $('.nav-create').click(function () {
            $('.md-modal').addClass('md-show');
        });

        $('.md-close').click(function () {
            $('.md-modal').removeClass('md-show');
        });

        $('.md-content form').on('submit', function (event) {
            event.preventDefault();

            let name = $('input[name="new-album-name"]').val();

            showLoader('loader');

            $.post(this.action, {name: name}).done(
                function (response) {
                    if (response.error) {
                        addError(response.message);
                        setTimeout(function () {
                                hideLoader('loader');
                        }, 3500);
                        return;
                    }

                    addSuccess(response.message);
                    setTimeout(function () {
                        window.location.href = response.url;
                    }, 350);
                }
            ).fail(
                function (response) {
                    addError(response.responseJSON.message);
                }
            );
        });
    </script>

@endsection