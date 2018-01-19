@extends('layouts.app')

<?php /** @var \App\Http\Api\Album $album */ ?>

@section('title', ucfirst($subdomain) . ' - Menu')

@section('header_scripts')
@endsection

@section('content')
    <div id="album-edit" class="wrapper">
        @include('dashboard.website.partial.header')

        <div class="container">
            <nav>
                <ul>
                    <li id="menu-templates" class="active">Choix du template</li>
                    <li id="menu-options">Options</li>
                </ul>
            </nav>

            <div id="steps">
                <div class="step-1">
                    <h2>Mes templates</h2>
                    <div class="ajax-updated">

                    </div>
                </div>

                <div class="step-2" style="display: none;">
                    <h2>Options</h2>
                    <form id="configurations" class="m-form">
                        <div class="input">
                            <input type="text" name="name"
                                   value="{{ isset($config[\App\Model\Menu::NAME]) ? $config[\App\Model\Menu::NAME] : '' }}"
                                   required>
                            <label>Nom du site</label>
                        </div>
                        <h3>Selectionnez les liens que vous voulez faire apparaitre</h3>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="accueil" {{ !empty($config[\App\Model\Menu::ACCUEIL]) ? 'checked' : '' }}>
                                Accueil</label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="albums" {{ !empty($config[\App\Model\Menu::ALBUMS]) ? 'checked' : '' }}>
                                Albums</label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="articles" {{ !empty($config[\App\Model\Menu::ARTICLES]) ? 'checked' : '' }}>
                                Articles</label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="events" {{ !empty($config[\App\Model\Menu::EVENTS]) ? 'checked' : '' }}>
                                Evenements</label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="reviews" {{ !empty($config[\App\Model\Menu::REVIEWS]) ? 'checked' : '' }}>
                                Avis</label>
                        </div>
                    </form>
                    <div class="options">
                        <div class="submit">
                            <span class="next">Valider</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.website.partial.modal', ['name' => 'preview-modal'])
    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script>
        let templateId = null;
        let currentTemplatePage = 1;

        @if(!empty($templateId))
            templateId = '{{ $templateId }}';
        @endif

        initMenu();
        updateTemplatesGrid();
        initSubmitEvent();

        function showTemplates() {
            $('.step-1').fadeIn();
            $('.step-2').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-templates').addClass('active');
        }

        function showOptions() {
            if (!templateId) {
                alert('No template selected');

                return;
            }

            $('.step-1').hide();
            $('.step-2').fadeIn();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-options').addClass('active');
        }

        function initMenu() {
            $("#menu-templates").click(showTemplates);
            $("#menu-options").click(showOptions);
        }

        function updateTemplatesGrid(withLocalData) {
            let updatedDiv = $('.step-1').find('.ajax-updated');
            updatedDiv.fadeOut();

            let url = '{{ route('dashboard.website.menu.templates.grid', ['subdomain' => $subdomain]) }}';

            let params = {page: currentTemplatePage};
            if (withLocalData) {
                params['templateId'] = templateId;
            }

            $.post(url, params).done(
                function (response) {
                    updatedDiv.html(response);
                    initTemplatePreviews();
                    initTemplatePagination();

                    updatedDiv.fadeIn();
                }
            ).fail(errorAjax)
        }

        function initTemplatePreviews() {
            $('#templates').find('.preview').click(function () {
                let target = $(this).data('target');
                templateId = $(this).data('id');

                $('#preview-modal').find('.md-content').html('');
                $.post('{{ route('dashboard.website.albums.templates.preview', ['subdomain' => $subdomain]) }}', {id: templateId}).done(
                    function (response) {
                        $('#preview-modal').find('.md-content').html(response);
                        initTemplatePreviewModal();
                    }
                ).fail(errorAjax);

                showModal(target);
            });
        }

        function initTemplatePagination() {
            let templatePagination = $('#templates').next('.options').find('.pagination');
            templatePagination.find('.next').click(function () {
                if ($(this).attr('disabled')) {
                    return;
                }

                currentTemplatePage += 1;
                updateTemplatesGrid();
            });

            templatePagination.find('.previous').click(function () {
                if (currentTemplatePage <= 1) {
                    return;
                }

                currentTemplatePage -= 1;
                updateTemplatesGrid();
            });

            $('#templates').next('.options').find('.submit').click(function () {
                showOptions();
            });
        }

        function initTemplatePreviewModal() {
            $('#template-cancel').click(function () {
                $('.md-close').trigger('click');
            });

            $('#template-submit').click(function () {
                let nav = $('nav ul');
                nav.find('li.active').removeClass('active');
                $(nav.find('li')[1]).addClass('active');

                updateTemplatesGrid(true);

                showOptions();

                $('.md-close').trigger('click');
            });
        }

        function initSubmitEvent() {
            $('#configurations').next('.options').find('.submit').click(function () {
                let options = $('#configurations').serializeArray();
                let data = {
                    'template': templateId,
                    'options': options
                };

                let url = '{{ route('dashboard.website.menu.save', ['subdomain' => $subdomain]) }}';

                $.post(url, data).done(
                    function (response) {
                        if (response.error) {
                            addError(response.message);
                            return;
                        }

                        addSuccess(response.message);
                        setTimeout(function () {
                            window.location.href = response.url;
                        }, 750);
                    }
                ).fail(errorAjax);
            })
        }
    </script>
@endsection