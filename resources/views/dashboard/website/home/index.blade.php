@extends('layouts.app')

@section('title', ucfirst($subdomain) . ' - Accueil')

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
                <span class="nav-title">Page d'accueil</span>
                <span class="nav-create">
                    <span>Sauvegarder</span>
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div id="home-config">
            <h2 class="empty">Vous n'avez aucun élément visible</h2>
        </div>
        <div class="controls">
            <div class="add-element">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    @include('dashboard.website.partial.loader')
    @include('dashboard.website.partial.modal', ['name' => 'categories-modal'])
    @include('dashboard.website.partial.modal', ['name' => 'blocks-modal'])
    @include('dashboard.website.partial.modal', ['name' => 'config-modal'])

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script>
        let config = [];

        $('.add-element').click(displayCategoriesModal);
        $('.nav-create').click(saveConfig);

        function displayCategoriesModal() {
            let updatedDiv = $('#categories-modal').find('.md-content');
            showLoader('loader');

            let url = '{{ route('dashboard.website.home.categories', ['subdomain' => $subdomain]) }}';

            $.post(url).done(
                function (response) {
                    updatedDiv.html(response);

                    showModal('categories-modal');
                    setTimeout(hideLoader, 350, 'loader');
                }
            ).fail(errorAjax);
        }

        function addBlock(blockConfig, preview) {
            config.push(blockConfig);
            preview = atob(preview);

            let blocksDiv = $('#home-config');
            let block = $('<div>').html(preview);
            blocksDiv.find('.empty').remove();

            blocksDiv.append(block);

            hideLoader('loader');
        }

        function saveConfig() {
            let url = '{{ route('dashboard.website.home.save', ['subdomain' => $subdomain]) }}';
            let data = {
                'blocks': config
            };

            $.post(url, data).done(
                function (response) {
                    console.log(response);
                }
            ).fail(errorAjax);
        }
    </script>

    <?php
            /** @var \App\Model\Website $website */
            $website = \App\Model\Website::where('subdomain', 'axolo')->first();
            $website->getHomeBlocks();
    ?>
@endsection