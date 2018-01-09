@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <?php /** @var \App\Http\Api\Album $album */ ?>
    <div id="album-edit" class="wrapper">
        @include('dashboard.website.header')

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
                        @include('dashboard.website.album.templates.preview-grid', ['templates' => $templates, 'selectedTemplate' => $templateId])
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
                        @include('dashboard.website.album.images.image-grid', ['photos' => $album->getPhotosByPage(1)])
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
                    <form id="configurations">
                        <p>
                            <label>
                                Titre de la page <br>
                                <input type="text" name="title"
                                       value="{{ isset($config[\App\Model\Album::TITLE]) ? $config[\App\Model\Album::TITLE] : '' }}">
                            </label>
                        </p>
                        <p>
                            <label>
                                Description <br>
                                <textarea name="description" cols="40"
                                          rows="3">{{ isset($config[\App\Model\Album::DESCRIPTION]) ? $config[\App\Model\Album::DESCRIPTION] : '' }}</textarea>
                            </label>
                        </p>
                        <p>
                            <label>
                                URL <br>
                                <input type="text" name="url"
                                       value="{{ isset($config[\App\Model\Album::URL]) ? $config[\App\Model\Album::URL] : '' }}">
                            </label>
                        </p>
                        <p>
                            <label>
                                Masquer les nouvelles images
                                <input type="checkbox"
                                       name="hide_new" {{ !empty($config[\App\Model\Album::HIDE_NEW]) ? 'checked' : '' }}>
                            </label>
                        </p>
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

    @include('dashboard.website.modal', ['name' => 'preview-modal'])
    @include('dashboard.website.modal', ['name' => 'image-modal'])

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script> // Specific
        let templateId = null;
        let currentTemplatePage = 1;
        let currentImagePage = 1;
        let imagesEdited = {};

        let templates = $('#templates');
        let images = $('#images');

        @if(!empty($templateId))
            templateId = '{{ $templateId }}';
        @endif

        initMenu();
        initTemplatePagination();
        initTemplatePreviews();
        initImagePagination();
        initImagePreviews();
        initSubmitEvent();

        function showTemplates() {
            $('.step-1').fadeIn();
            $('.step-2').hide();
            $('.step-3').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-templates').addClass('active');
        }

        function showImages() {
            if (!templateId) {
                alert('No template selected');

                return;
            }

            $('.step-1').hide();
            $('.step-2').fadeIn();
            $('.step-3').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-images').addClass('active');
        }

        function showOptions() {
            if (!templateId) {
                alert('No template selected');

                return;
            }

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
                showImages();
            });
        }

        function updateTemplatesGrid(withLocalData) {
            templates.fadeOut();
            templates.next('.options').fadeOut();

            let url = '{{ route('dashboard.website.albums.templates.grid', ['subdomain' => $subdomain, 'id' => $album->getId()]) }}';

            let params = {page: currentTemplatePage};
            if (withLocalData) {
                params['templateId'] = templateId;
            }

            $.post(url, params).done(
                function (response) {
                    templates.html(response);
                    initTemplatePreviews();

                    templates.fadeIn();
                    templates.next('.options').fadeIn();
                }
            ).fail(errorAjax)
        }

        function initTemplatePreviews() {
            templates.find('.preview').click(function () {
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

        function initTemplatePreviewModal() {
            $('#template-cancel').click(function () {
                $('.md-close').trigger('click');
            });

            $('#template-submit').click(function () {
                let nav = $('nav ul');
                nav.find('li.active').removeClass('active');
                $(nav.find('li')[1]).addClass('active');

                updateTemplatesGrid(true);

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
            images.find('.visibility').each(function () {
                let id = $(this).parent().data('id');

                if (id in imagesEdited) {
                    setVisibility(!imagesEdited[id].visible, this);
                }

                $(this).click(changeVisibility)
            });

            function setVisibility(visible, visibilityDiv) {
                let icon = $(visibilityDiv).find('i');

                if (visible) {
                    icon.removeClass('fa-eye');
                    icon.addClass('fa-eye-slash');
                    icon.attr('data-changed', 1)
                } else {
                    icon.removeClass('fa-eye-slash');
                    icon.addClass('fa-eye');
                    icon.attr('data-changed', 1)
                }
            }

            function changeVisibility() {
                let icon = $(this).find('i');
                let visible = icon.hasClass('fa-eye');
                let id = $(this).parent().data('id');

                imagesEdited[id] = {
                    visible: !visible
                };

                setVisibility(visible, this);
            }

            images.find('.view').click(function () {
                let id = $(this).parent().data('id');
                let url = '{{ route('dashboard.website.albums.images.preview', ['subdomain' => $subdomain, 'id' => $album->getId()]) }}';
                let imageModal = $('#image-modal');

                imageModal.find('.md-content').html('');
                $.post(url, {id: id}).done(
                    function (response) {
                        imageModal.find('.md-content').html(response);
                    }
                ).fail(errorAjax);

                showModal('image-modal');
            });
        }

        function initSubmitEvent() {
            $('#configurations').next('.options').find('.submit').click(function () {
                let options = $('#configurations').serializeArray();
                let data = {
                    'template': templateId,
                    'images': imagesEdited,
                    'options': options
                };

                let url = '{{ route('dashboard.website.albums.save', ['subdomain' => $subdomain, 'id' => $album->getId()]) }}';

                $.post(url, data).done(
                    function () {

                    }
                ).fail(errorAjax());
            })
        }
    </script>

    <script>
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