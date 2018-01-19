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
                            <input type="text" name="title"
                                   value="{{ isset($config[\App\Model\Album::TITLE]) ? $config[\App\Model\Album::TITLE] : '' }}"
                                   required>
                            <label>Titre de la page</label>
                        </div>
                        <div class="input">
                            <input type="text" name="url"
                                   value="{{ isset($config[\App\Model\Album::URL]) ? $config[\App\Model\Album::URL] : '' }}"
                                   required>
                            <label>URL</label>
                        </div>
                        <div class="input">
                            <textarea name="description" cols="40"
                                      rows="3">{{ isset($config[\App\Model\Album::DESCRIPTION]) ? $config[\App\Model\Album::DESCRIPTION] : '' }}</textarea>
                            <label>Description</label>
                        </div>
                        <div class="checkbox">

                            <label>
                                <input type="checkbox"
                                       name="hide_new" {{ !empty($config[\App\Model\Album::HIDE_NEW]) ? 'checked' : '' }}>
                                Masquer les nouvelles images</label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="visible" {{ !empty($config[\App\Model\Album::VISIBLE]) ? 'checked' : '' }}>
                                Album visible</label>
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
                showImages();
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
    </script>
@endsection