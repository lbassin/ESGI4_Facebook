@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div id="album-edit" class="wrapper">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userHelper->getPicture() }}" alt="">
            </div>
            <span class="user-name">{{ $userHelper->getName() }}</span>
        </div>

        <div class="container">
            <nav>
                <ul>
                    <li id="menu-templates" class="active">Choix du template</li>
                    <li id="menu-images">Selection des images</li>
                    <li id="menu-options">Options</li>
                </ul>
            </nav>

            <div id="steps">
                <div class="step-1">
                    <h2>Mes templates</h2>
                    <div id="templates" class="preview-grid">
                        @include('dashboard.website.album.templates.preview-grid', ['templates' => $templates])
                    </div>
                    <div class="options">
                        <div class="pagination">
                            <div class="controls">
                                <span class="previous">Précedent</span>
                                <span class="next">Suivant</span>
                            </div>
                        </div>
                        <div class="submit">
                            <span class="next">Valider</span>
                        </div>
                    </div>
                </div>

                <div class="step-2" style="display: none;">
                    <h2>Mes images</h2>
                    <div id="images" class="preview-grid">
                        @include('dashboard.website.album.images.preview-grid', ['photos' => $album->getPhotosByPage(1)])
                    </div>
                    <div class="options">
                        <div class="pagination">
                            <div class="controls">
                                <span class="previous">Précedent</span>
                                <span class="next">Suivant</span>
                            </div>
                        </div>
                        <div class="submit">
                            <span class="next">Valider</span>
                        </div>
                    </div>
                </div>

                <div class="step-3" style="display: none;">
                    <h2>Options</h2>
                    <div>
                        //
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="preview-modal" class="md-modal md-effect-12">
        <div class="md-content">
            <!-- Ajax -->
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script> // Specific
        let templateId = null;
        let currentTemplatePage = 1;
        let currentImagePage = 1;

        let templates = $('#templates');
        let images = $('#images');

        initMenu();
        initTemplatePagination();
        initTemplatePreviews();
        initImagePagination();

        function showTemplates() {
            $('.step-1').fadeIn();
            $('.step-2').hide();
            $('.step-3').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-templates').addClass('active');
        }

        function showImages() {
            $('.step-1').hide();
            $('.step-2').fadeIn();
            $('.step-3').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-images').addClass('active');
        }

        function showOptions() {
            $('.step-1').hide();
            $('.step-2').hide();
            $('.step-3').fadeIn();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-options').addClass('active');
        }

        function initMenu() {
            $("#menu-templates").click(showTemplates);
            $("#menu-images").click(showImages);
            $("#menu-options").click(showOptions);
        }

        function initTemplatePagination() {
            let templatePagination = templates.next('.options').find('.pagination');
            templatePagination.find('.next').click(function () {
                // TODO  : Check if last page

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
                if (!templateId) {
                    alert('No template selected');

                    return;
                }

                showImages();
            });

            function updateTemplatesGrid() {
                templates.fadeOut();
                templates.next('.options').fadeOut();

                let url = '{{ route('dashboard.website.albums.templates.grid', ['subdomain' => $subdomain]) }}';

                $.post(url, {page: currentTemplatePage}).done(
                    function (response) {
                        templates.html(response);
                        initTemplatePreviews();

                        templates.fadeIn();
                        templates.next('.options').fadeIn();
                    }
                ).fail(errorAjax)
            }
        }

        function initTemplatePreviews() {
            templates.find('.preview').click(function () {
                let target = $(this).data('target');
                templateId = $(this).data('id');

                $.post('{{ route('dashboard.website.albums.template.preview', ['subdomain' => $subdomain]) }}', {id: templateId}).done(
                    function (response) {
                        $('#preview-modal').find('.md-content').html(response);
                        initTemplatePreviewModal();
                    }
                ).fail(errorAjax);

                showModal(target);
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

                showImages();

                $('.md-close').trigger('click');
            });
        }

        function initImagePagination() {
            let imagePagination = images.next('.options').find('.pagination');
            imagePagination.find('.next').click(function () {
                // TODO  : Check if last page

                currentImagePage += 1;
                updateImagesGrid();
            });

            imagePagination.find('.previous').click(function () {
                if (currentImagePage <= 1) {
                    return;
                }

                currentImagePage -= 1;
                updateImagesGrid();
            });

            $('#images').next('.options').find('.submit').click(showOptions);

            function updateImagesGrid() {
                images.fadeOut();
                images.next('.options').fadeOut();

                let url = '{{ route('dashboard.website.albums.images.grid', ['subdomain' => $subdomain, 'id' => $album->getId()]) }}';

                $.post(url, {page: currentImagePage}).done(
                    function (response) {
                        images.html(response);
                        initImagePreviews();

                        images.fadeIn();
                        images.next('.options').fadeIn();
                    }
                ).fail(errorAjax)
            }
        }

        function initImagePreviews() {

        }
    </script>

    <script> // Global
        function showModal(target) {
            $('#' + target).addClass('md-show');
        }

        $(document).on('keydown', function (event) {
            if (event.keyCode === 27) {
                $('.md-close').trigger('click');
            }
        });

        function hideModal(target) {
            $('#' + target).removeClass('md-show');
        }

        $('.md-close').click(function () {
            $('.md-modal').each(function () {
                hideModal($(this).attr('id'));
            });
        });

        function errorAjax() {
            alert('An error occurred');
        }
    </script>

@endsection