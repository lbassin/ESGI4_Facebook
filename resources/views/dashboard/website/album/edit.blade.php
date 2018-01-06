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
                        @foreach($templates as $template) <?php /** @var \App\Model\Template $template */ ?>
                        <div class="preview" data-target="modal-preview" data-id="{{ $template->getId() }}">
                            <img src="{{ $template->getDesktopPreview() }}" alt="">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="step-2" style="display: none;">
                    <h2>Mes images</h2>
                    <div id="images" class="preview-grid">
                        @foreach($album->getPhotos() as $photo) <?php /** @var \App\Http\Api\Photo $photo */ ?>
                        <div class="preview" data-target="modal-preview" data-id="1">
                            <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="">
                        </div>
                        @endforeach
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

    <div id="modal-preview" class="md-modal md-effect-12">
        <div class="md-content">
            <h1>Mise en page</h1>
            <div>
                <h2>Ordinateur</h2>
                <img class="desktop-preview" src="" alt="" style="width: 50%">
            </div>
            <div>
                <h2>Mobile</h2>
                <img class="mobile-preview" src="" alt="" style="height: 50%">
            </div>
            <div>
                <button id="template-cancel">Cancel</button>
                <button id="template-submit">Valider</button>
            </div>
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script> // Specific
        let templateId = null;

        $('#templates .preview').click(function () {
            let target = $(this).data('target');
            templateId = $(this).data('id');

            $.post('{{ route('dashboard.website.albums.template', ['subdomain' => $subdomain]) }}', {id: templateId}).done(
                function (response) {
                    let modal = $('#modal-preview');

                    modal.find('.desktop-preview').attr('src', response.desktop_preview);
                    modal.find('.mobile-preview').attr('src', response.mobile_preview);
                }
            ).fail(
                function () {
                    alert('An error occurred');
                }
            );

            showModal(target);
        });

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

        function showTemplates() {
            $('.step-1').show();
            $('.step-2').hide();
            $('.step-3').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-templates').addClass('active');
        }

        function showImages() {
            $('.step-1').hide();
            $('.step-2').show();
            $('.step-3').hide();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-images').addClass('active');
        }

        function showOptions() {
            $('.step-1').hide();
            $('.step-2').hide();
            $('.step-3').show();

            $('nav ul li').removeClass('active');
            $('nav ul #menu-options').addClass('active');
        }

        $("#menu-templates").click(showTemplates);
        $("#menu-images").click(showImages);
        $("#menu-options").click(showOptions);
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
    </script>

@endsection