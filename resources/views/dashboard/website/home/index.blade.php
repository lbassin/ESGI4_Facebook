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
        let savedConfig = atob("{{ $config }}");

        JSON.parse(savedConfig).forEach(function (block) {
            addBlock(block.config, block.preview);
        });

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

            let position = -1;
            blockConfig.forEach(function (data, index) {
                if (data.name === 'position') {
                    position = data.value;
                    blockConfig.splice(index, 1);
                }
            });

            if (position >= 0) {
                config[position] = blockConfig;
                return;
            }

            preview = $.parseHTML(atob(preview));

            let blocksDiv = $('#home-config');
            let overlay = $('<div>').addClass('overlay');
            let controls = $('<div>').addClass('controls');
            let controlEdit = $('<div>').addClass('edit');
            let controlRemove = $('<div>').addClass('remove');
            let block = $('<div>').html(preview);

            controls.data('target', config.length);

            controlEdit.html('<i class="fa fa-pencil"></i>');
            controlRemove.html('<i class="fa fa-trash" aria-hidden="true"></i>');

            controls.append(controlEdit).append(controlRemove);
            overlay.append(controls);
            block.append(overlay);

            $(block).attr('data-id', config.length);

            blocksDiv.find('.empty').remove();

            $(controlEdit).on('click', editBlock);
            $(controlRemove).on('click', removeBlock);
            blocksDiv.append(block);
            config.push(blockConfig);
        }

        function saveConfig() {
            let url = '{{ route('dashboard.website.home.save', ['subdomain' => $subdomain]) }}';
            let data = {'blocks': config};

            $.post(url, data).done(
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
            ).fail(errorAjax);
        }

        function removeBlock() {
            let id = $(this).parent().data('target');
            let target = $('[data-id="' + id + '"]');

            target.remove();
            config[id] = [];
        }

        function editBlock() {
            let id = $(this).parent().data('target');

            let blockConfig = config[id];

            let blockId = 0;
            blockConfig.forEach(function (data) {
                if (data.name === 'block_id') {
                    blockId = data.value;
                }
            });

            showLoader('loader');
            setTimeout(hideModal, 350, 'blocks-modal');

            let updatedDiv = $('#config-modal').find('.md-content');
            let url = '{{ route('dashboard.website.home.block.config', ['subdomain' => $subdomain]) }}';

            $.post(url, {block_id: blockId, config: blockConfig, position: id}).done(
                function (response) {
                    updatedDiv.html(response);

                    showModal('config-modal');
                    setTimeout(hideLoader, 350, 'loader');
                }
            ).fail(errorAjax);
        }
    </script>
@endsection